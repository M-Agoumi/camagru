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

class Request
{
	public function getPath()
	{
		$path = $_SERVER['REQUEST_URI'] ?? '/';
		$position = strpos($path, '?');
		if ($position)
		{
			echo "hello";
		}
	}

	public function getMethod()
	{

	}

	public function getBody()
	{

	}
}