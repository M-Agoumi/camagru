<?php


namespace Simfa\Framework\Middleware;


use Simfa\Framework\Application;
use Simfa\Model\BannedIP;

class FirewallMiddleware extends BaseMiddleware
{
	/**
	 * check if ip is banned
	 */
	public function execute()
	{
		$ip = Application::$APP->request->getUserIpAddress();
		$banned = new BannedIP();

		$banned->getOneBy('address', $ip);

		if ($banned->id)
			die(
				'sorry you are banned for the following reason: ' . $banned->comment . '<br>' .
				'if you think this was a mistake please feel free to contact us on <a href="mailto:' .
				Application::getEnvValue('SUPPORT_MAIL') . '">' . Application::getEnvValue('SUPPORT_MAIL') .'</a>'
			);
	}

}
