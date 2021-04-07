<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        ::::::::            */
/*   Request.php                                        :+:    :+:            */
/*                                                     +:+                    */
/*   By: magoumi <magoumi@student.1337.ma>            +#+                     */
/*                                                   +#+                      */
/*   Created: 2021/03/16 17:42:36 by null          #+#    #+#                 */
/*   Updated: 2021/03/16 17:42:36 by null          ########   odam.nl         */
/*                                                                            */
/* ************************************************************************** */

namespace core;

/**
 * Class Request
 */

class Request
{
	/**
	 * @return string the current path of the application
	 */
	public function getPath(): string
	{
		$path = $_SERVER['REQUEST_URI'] ?? '/';
		$position = strpos($path, '?');
		if ($position === false)
			return $path;

		return substr($path, 0, $position);
	}

	/**
	 * @return string the method of our request [get|post] in lowercase
	 */
	public function Method(): string
	{
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * @return bool
	 */
	public function isGet():bool
	{
		return $this->Method() === 'get';
	}

	/**
	 * @return bool
	 */
	public function isPost():bool
	{
		return $this->Method() === 'post';
	}

	/**
	 * handle the data coming from a form
	 * todo add more filters for more security
	 * @return array
	 */
	public function getBody(): array
	{
		$body = [];
		if ($this->Method() === 'get') foreach ($_GET as $key => $value) {
				$body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
			}
		if ($this->Method() === 'post') foreach ($_POST as $key => $value) {
			$body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
		}

		return $body;
	}

    /**
     * @return array|bool
     */
    public function magicPath()
    {
        $x = $this->getRoutes();
        $path = $this->getMagicPath($this->getPath());
//        $path = $path;
        $ret = null;
//        print_r($x);
//        echo "<br>$path <br>";
        foreach ($x as $key => $value) {
            // print_r($value);
            if (isset($value[$path])) {
                $ret = $value[$path];
                array_push($ret, $this->getPathVar());
                break;
            }
        }
//        echo "our return value: <br>";
//        var_dump($ret);
//        die();
        return $ret ?? false;
    }

    public function getRoutes(): array
    {
        $routes = Application::$APP->router->routes['magic'] ?? [];
        $newRoutes = [];
//        echo 'old: <pre>';
//        print_r($routes);
//        echo '<br>';
        foreach ($routes as $key => $value)
        {
            $key = preg_replace( '~\{.*\}~' , "", $key);
            $key = substr_replace($key ,"", -1);
            $newRoutes[] = array($key => $value);

        }
        return $newRoutes;
    }

    private function getMagicPath(string $path)
    {
        $position = strrpos($path, '/');
        return substr($path, 0, $position);
    }

    private function getPathVar()
    {
        $path = $this->getPath();
//        echo "path: $path<br>";
        $position = strrpos($path, '/');
//        echo "position: $position <br>";
        return (substr($path, $position + 1));
    }
}