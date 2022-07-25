<?php

namespace Simfa\Framework;

use Exception;
use ReflectionClass;
use ReflectionException;
use Simfa\Framework\Db\DbModel;
use Simfa\Framework\Exception\ExpiredException;
use Simfa\Framework\Exception\NotFoundException;

class Injector
{

	/**
	 * @var bool
	 */
	private bool $autowired = false;

	/**
	 * @param $class
	 * @param string $method
	 * @return false|mixed|object|null
	 * @throws ExpiredException
	 * @throws NotFoundException
	 */
	public function getInstance($class, string $method = '__construct'): mixed
	{
		if ($method == '__construct' && !method_exists($class, $method))
			return new $class();

		try {
			$params = $this->getDependencies($class, $method);
			$rf = new ReflectionClass($class);

			return $rf->newInstanceArgs($params);
		} catch (ReflectionException $e) {
			Application::$APP->catcher->catch($e);
		}

		return false;
	}

	/**
	 * @param $class
	 * @param $method
	 * @param string|null $key
	 * @param string|null $value
	 * @return array|null
	 * @throws ExpiredException
	 * @throws NotFoundException
	 */
	public function getDependencies($class, $method, ?string $key = null, ?string $value = null): ?array
	{
		try {

			$params = [];
			$reflector = new ReflectionClass($class);

			foreach ($reflector->getMethod($method)->getParameters() as $param) {
				$modelName = $param->name;
				$modelType = $param->getClass()->name ?? NULL;

				if ($modelType)
					$params[] = $this->injectClassOrModule($modelType, $modelName, $key, $value);
				else
					$params[] = $value;
			}

			return $params;
		} catch (ReflectionException $e) {
			Application::$APP->catcher->catch($e);
		}

		return NULL;
	}

	/**
	 * @param $type
	 * @param $name
	 * @param $key
	 * @param $value
	 * @return mixed
	 * @throws ExpiredException
	 * @throws NotFoundException
	 * @throws ReflectionException
	 */
	protected function injectClassOrModule($type, $name, $key = null, $value = null): mixed
	{
		if (Application::isAppProperty($name) && $name != 'user') /** todo: make dynamic instead of user */
			return Application::$APP->$name;
		else
			return $this->injectModule($type, $key, $value);
	}

	/** inject module
	 * @param $type
	 * @param $key
	 * @param $value
	 * @return object
	 * @throws ExpiredException
	 * @throws NotFoundException
	 * @throws ReflectionException
	 */
	private function injectModule($type, $key, $value): object
	{
		if (class_exists($type))
			return $this->createModuleInstance($type, $key, $value);

		throw new Exception('class ' . $type . ' not found while trying to inject it');
	}

	/**
	 * @throws ReflectionException
	 * @throws ExpiredException
	 * @throws NotFoundException
	 */
	private function createModuleInstance(string $type, $key, $value): object
	{
		/** @var  $arguments array of constructor arguments */
		$arguments = $this->getClassConstructorArguments($type);
		$reflector = new ReflectionClass($type);
		$name = $reflector->getShortName();

		$instance = $reflector->newInstanceArgs($arguments);

		if ($this->autowired)
			return $instance;
		$this->autowired = true;

		$relations = [];

		if (method_exists($instance, 'relationships'))
			$relations = $instance->relationships();

		if ($instance instanceof DbModel) {
			$primaryKey = $key ?? '';

			if ($name == $primaryKey)
				$primaryKey = $instance->primaryKey();

			if ($primaryKey) {
				if (property_exists($type, $primaryKey)) {
					$instance = $instance::findOne([$primaryKey => $value], $relations);

					if (!$instance->getId())
						if (str_contains($key, 'token'))
							throw new ExpiredException('Invalid token');
						else
							throw new NotFoundException();
				} else
					throw new Exception($key . ' not found in class ' . $type);
			}
		}

		return $instance;
	}

	/**
	 * @param string $type
	 * @return array|null
	 * @throws ExpiredException
	 * @throws NotFoundException
	 * @throws ReflectionException
	 */
	private function getClassConstructorArguments(string $type): ?array
	{
		$rf = new ReflectionClass($type);

		$constructorRef = $rf->getConstructor();
		$constructorArguments = $constructorRef ? $constructorRef->getParameters() : [];
		if (!count($constructorArguments))
			return [];

		$arguments = [];

		foreach ($constructorArguments as $argument)
		{
			$type = $argument->getType();
			$name = $argument->getName();
			$arguments[] = $this->injectClassOrModule($type, $name);
		}

		return $arguments;
	}
}
