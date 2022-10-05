<?php

namespace Service;

class Mailer
{
	private function mailer(string $to, string $subject, string $body, string $headers): bool
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
			$body = render('mails/' . $content[0], $content[1]);

			return $this->mailer($to, $subject, $body, $headers);
		} else
			return $this->mailer($to, $subject, $content, $headers);
	}
}
