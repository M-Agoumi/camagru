<?php


namespace core\Middleware;


use core\Application;
use models\core\BannedIP;

class FirewallMiddleware extends BaseMiddleware
{

	public function execute()
	{
		$ip = Application::$APP->request->getUserIpAddress();
		$banned = new BannedIP();

		$banned->getOneBy('address', $ip);

		if ($banned->id)
			die(
				'sorry your are banned for the following reason: '.$banned->comment . '<br>' .
				'if you think this was a mistake please feel free to contact us on <a href="mailto:' .
				Application::getEnvValue('supportMail') . '">' . Application::getEnvValue('supportMail') .'</a>'
			);
	}

}
