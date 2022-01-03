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

namespace Simfa\Action;

use Simfa\Framework\Application;
use Simfa\Framework\Middleware\BaseMiddleware;
use Simfa\Framework\Middleware\FirewallMiddleware;

/**
 * Class Controller
 * base Controller to extend other controllers from it
 */

abstract class Controller
{

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


	public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

	public function mailer(string $to, string $subject, string $body, string $headers): bool
	{
		if (mail($to, $subject, $body, $headers))
			return true;

		return false;
	}

    public function mail($to, $subject ,$content, string $from = 'admin@camagru.io'):bool
    {
	    $headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
		    'From: ' . $from . "\r\n" .
		    'Reply-To: reply@camagru.io' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();

    	if (is_array($content)) {
    		$body = $this->render('mails/' . $content[0], $content[1], ['title' => $subject]);

    		return $this->mailer($to, $subject, $body, $headers);
	    } else
    	    return $this->mailer($to, $subject, $content, $headers);
    }

	public function slugify($text, string $divider = '-'): string
	{
		// replace non letter or digits by divider
		$text = preg_replace('~[^\pL\d]+~u', $divider, $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, $divider);

		// remove duplicate divider
		$text = preg_replace('~-+~', $divider, $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}

	protected function json($value)
	{
		return json_encode($value);
	}
}
