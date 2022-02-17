<?php

namespace Simfa\Framework\CLI\Commands;

use Simfa\Framework\CLI\CLIApplication;

class MakeEntity
{
	/**
	 * @var array allowed property types
	 */
	private array $propertyTypes = ['string', 'int', 'boolean', 'text'];

	/**
	 * @var array|string[] property which needs a predefined length with the length
	 */
	private array $hasLength =  [
									'string' => '255',
									'int' => '11'
								];

	/**
	 * make an entity
	 * @param $argv
	 */
	public function entity($argv): void
	{
		/** entity name */
		$entityName = $argv[1] ?? $this->readEntityName();

		/** entity table name */
		$entityTable = $this->getEntityTable($entityName);

		/** entity components */
		$entityComponents = $this->readEntityProperties();

		/** create entity module class */
		$this->createEntityCLassFile($entityName, $entityTable, $entityComponents);
	}

	private function readEntityName():string
	{
		echo GREEN . 'Please provide Entity name: ' . PHP_EOL . CYAN;
		$name = readline();
		echo RESET;

		return ucfirst($name);
	}

	private function getEntityTable(string $entityName): string
	{
		$entityTable = strtolower($entityName) . 's';

		echo GREEN . "Provide table name if you wish to name it differently [" . YELLOW . $entityTable . GREEN . "]" .
			PHP_EOL . RESET;
		$read = readline();

		if ($read != '')
			return $read;

		return $entityTable;
	}


	private function readEntityProperties():array
	{
		/** properties of the entity */
		$properties = [];

		while (true) {
			$name = $this->readPropertyName();

			/** check if user wanted to stop adding new values */
			if ($name == '')
				break;

			/** get property type */
			$type = $this->readPropertyType();

			/** get property size */
			$length = isset($this->hasLength[$type]) ? $this->readPropertyLength($type) : 0;

			$nullable = $this->readPropertyNullable();

			array_push($properties, [$name, $type, $length, $nullable]);
		}

		return $properties;
	}

	private function readPropertyName(): string
	{
		echo GREEN . 'Please provide property name: ' . PHP_EOL . CYAN;
		$name = readline();
		echo RESET;

		return str_replace(' ', '-', $name);
	}

	private function readPropertyType(): string
	{
		$type = $this->getPropertyType();

		return $this->validatePropertyType($type);
	}

	private function readPropertyNullable(): bool
	{
		echo GREEN . "Can this property be null in the database (nullable) (yes/no)[" . YELLOW . "no" . GREEN ."]" . PHP_EOL . CYAN;
		$nullable = strtolower(readline());

		if ($nullable == 'yes' || $nullable == 'Y')
			return true;

		return false;
	}

	private function getPropertyType()
	{
		echo GREEN . 'Please provide property type(type ? to see all supported types): ' . PHP_EOL . CYAN;
		$name = readline();
		echo RESET;

		if ($name == '?')
			return $this->displayPropertyTypes();
		return $name;
	}

	private function displayPropertyTypes()
	{
		foreach ($this->propertyTypes as $propertyType)
		{
			echo $propertyType . PHP_EOL;
		}

		return $this->getPropertyType();
	}

	private function validatePropertyType($type)
	{
		if (in_array($type, $this->propertyTypes))
			return $type;

		echo YELLOW . "Property type isn't valid" . RESET . PHP_EOL;

		return $this->getPropertyType();
	}

	private function readPropertyLength($type): int
	{
		echo GREEN . "Please provide property length [" . YELLOW . $this->hasLength[$type] . GREEN.  "]" . PHP_EOL;
		$length = readline();

		if ($length != '')
			return intval($length);

		return $this->hasLength[$type];
	}

	private function createEntityCLassFile(string $entityName, string $entityTable, array $entityComponents)
	{
		/** @var string $template get class template and stop script in case it weren't found */
		$templateFileName = CLIApplication::$ROOT_DIR . 'vendor/Simfa/Views/Commands/templates/entity.template';
		$template = file_get_contents($templateFileName);

		if (!$template)
			die(RED . 'an essential file for this command is not found. file name :' . $templateFileName . PHP_EOL . RESET);

		/** generate file name and set it to the header */
		$classFileName = $entityName . '.php' . str_repeat(' ', 50 - strlen($entityName . '.php'));
		$template = str_replace('{{ file_name }}', $classFileName, $template);

		/** generate date for the header and set it in the template */
		$classHeaderTime = date("Y/m/d G:i:s");
		$template = str_replace('{{ date_time }}', $classHeaderTime, $template);

		/** set the class name */
		$template = str_replace('{{ class_name }}', $entityName, $template);

		/** change class properties from array to string */
		$properties = $this->propertiesToString($entityComponents);

		/** set properties in the template */
		$template = str_replace('{{ class_properties }}', $properties, $template);

		/** change table name */
		if (ucfirst($entityName) != $entityTable)
			$template = str_replace('{{ table_name }}',
				'protected static string $tableName = "' . $entityTable . '";', $template);
		else
			$template = str_replace('{{ table_name }}', '', $template);

		/** add required rule for no nullable properties */
		$template = str_replace('{{ rules }}', $this->getRulesForProperties($entityComponents), $template);

		if (file_put_contents(CLIApplication::$ROOT_DIR. "src/Model/" . $entityName . ".php", $template))
			echo "class created successfully\n";
		else
			echo "something went wrong while writing to the file\n";
	}

	private function propertiesToString(array $entityComponents): string
	{
		$string = 'protected ?int $entityID = null;' . "\n\t";
		foreach ($entityComponents as $component)
		{
			$string .= "protected ?" . $component[1] . " $" . $component[0] . " = NULL;";
			$string .= "\n\t";
		}

		return $string;
	}

	private function getRulesForProperties($properties): string
	{
		$rules = '';
		foreach ($properties as $property)
		{
			$rule = '';
			if ($property[3] == false)
			{
				$rule .= "self::RULE_REQUIRED";
			}

			if ($property[2])
			{
				$rule .= $rule != '' ? ',' : '';
				$rule .= "[self::RULE_MAX, 'max' => " . $property[2] ."]";
			}

			$rules .= $rules != '' ? ",\n\t\t\t" : '';
			/** 'name' => [self::RULE_REQUIRED], */
			$rules .=  "'" . $property[0] . "' => " . "[" . $rule . "]";
		}


		return $rules;
	}
}
