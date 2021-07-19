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

namespace controller;

use core\Application;
use core\Middleware\BaseMiddleware;
use core\Middleware\FirewallMiddleware;

/**
 * Class Controller
 * base controller to extend other controllers from it
 */

abstract class Controller
{

	public string $layout = 'main';
	public string $action = '';
    /** @var BaseMiddleware[] */
	protected array $middlewares = [];

    /** adding this method to avoid typing it in every method in our controllers
     * @param string $view
     * @param array $params
     * @param array $layParams
     * @return false|string|string[]
     */
	public function render(string $view, array $params = [], array $layParams = [])
	{
		return Application::$APP->view->renderView($view, $params, $layParams);
	}

    /**
     * @return BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
    	array_push($this->middlewares, New FirewallMiddleware());

        return $this->middlewares;
    }

    /**
	 * change the used layout in the request
	 * @param string $string
	 */
	protected function setLayout(string $string)
	{
		$this->layout = $string;
	}

	public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

	public function mailer(string $to, string $subject, string $body): bool
	{
		if (mail($to, $subject, $body))
			return true;

		return false;
	}

    public function mail($to, $subject ,$content):bool
    {
	    $headers = 'From: webmaster@yourdot.com' . "\r\n" .
		    'Reply-To: agoumihunter@gmail.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();

    	if (is_array($content)) {
			$swap = $this->layout;
    		$this->layout = 'mail';
    		$body = $this->render('mails/' . $content[0], $content[1], ['title' => $subject]);
    		$this->layout = $swap;

    		return $this->mailer($to, $subject, $body, $headers);
	    } else
    	    return $this->mailer($to, $subject, $content, $headers);
    }
}
