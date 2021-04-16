<?php
# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    Response.php                                       :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: magoumi <agoumi.mohamed@outlook.com>       +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2021/03/16 20:53:26 by magoumi           #+#    #+#              #
#    Updated: 2021/03/16 20:53:26 by magoumi          ###   ########lyon.fr    #
#                                                                              #
# **************************************************************************** #

namespace core;

/**
 * Class Response
 */
class Response
{
	/**
	 * @param mixed $code the server response code
	 */
	public function setStatusCode($code)
	{
		if (is_string($code))
			$code = (int)$code;
		http_response_code($code);
	}
	
	/**
	 * @param string $url
	 * @return bool
	 */
	public function redirect(string $url): bool
	{
		header('Location: '. $url);
		return false;
	}
}