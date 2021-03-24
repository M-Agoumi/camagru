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

/**
 * Class Response
 */
class Response
{
	/**
	 * @param int $code the server response code
	 */
	public function setStatusCode(int $code)
	{
		http_response_code($code);
	}
}