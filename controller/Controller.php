<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Controller.php                                    :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/17 11:42:08 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/17 11:42:08 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

/**
 * Class Controller
 * base controller to extend other controllers from it
 */

abstract class Controller
{

	public string $layout = 'main';

	/** adding this method to avoid typing it in every method in our controllers
	 * @param string $view
	 * @param array $params
	 * @return false|string|string[]
	 */
	public static function render(string $view, array $params = [])
	{
		return Application::$APP->router->renderView($view, $params);
	}

	/**
	 * change the used layout in the request
	 * @param string $string
	 */
	protected function setLayout(string $string)
	{
		$this->layout = $string;
	}
}