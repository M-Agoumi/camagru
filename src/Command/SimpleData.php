<?php
/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   SimpleData.php                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: magoumi <magoumi@student.1337.m            +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2022/02/6 18:38:44 by magoumi            #+#    #+#             */
/*   Updated: 2022/02/6 18:38:44 by magoumi           ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */


namespace Command;

use Model\Comments;
use Model\Post;
use Model\User;
use Simfa\Framework\CLI\BaseCommand;
use FakeData\FakeDataFactory;
use Simfa\Framework\CLI\CLIApplication;

class SimpleData extends BaseCommand
{
	/**
	 * @var string
	 */
	protected static string $command = 'simpleData';

	public function __construct()
	{
		CLIApplication::$CLI_APP->getApp();
	}

	public function post(): string
	{
		$images = $this->getImages();
		$imagesCount = count($images);
		echo GREEN . 'Generating ' . $imagesCount . ' posts' . RESET . PHP_EOL;
		$fake = FakeDataFactory::create();

		for ($i = 0; $i < $imagesCount; $i++)
		{
			$post = new Post();

			$post->title = $fake->sentence;
			$hashtag = str_replace('#', '', $fake->hashtag(1));
			$post->comment = $fake->text(5, 30) . ' #' . $hashtag . ' ' . $fake->hashtag(2);
			$post->picture = $images[$i];
			$post->slug = $fake->slugify($post->title);
			$post->author = User::findOne(['entityID' => $fake->model(User::class)]);
			$post->status = 0;
			$post->save();
		}

		return (BLUE . 'Posts generating completed' . PHP_EOL . RESET);
	}

	public function user($argv = null): string
	{
		$users_number = 20;
		if (isset($argv[1]) && is_numeric($argv[1]))
			$users_number = $argv[1];

		echo GREEN . 'Generating ' . $users_number . ' users' . RESET . PHP_EOL;
		$fake = FakeDataFactory::create();

		for ($i = 0; $i < $users_number; $i++)
		{
			$user = new User();
			$user->name = $fake->name;
			$user->username = $fake->username($user->name);
			$user->email = $fake->email($user->name);
			$user->password = "P@ssw0rd!";
			$user->save();
		}

		return (BLUE . 'Users generating completed' . PHP_EOL . RESET);
	}

	public function comment(): string
	{
		$posts = new Post();
		$posts = $posts->findAll(0);
		$fake  = FakeDataFactory::create();

		echo YELLOW . "Adding comments to post:" . RESET . PHP_EOL;
		foreach ($posts as $post)
		{
			$commentsNumber = $fake->number(0, 10);
			echo BLUE . $post['entityID'] . GREEN .  ' => ' . WHITE . $post['title'] . RESET . PHP_EOL;

			for ($i = 0; $i < $commentsNumber; $i++) {
				$comment = new Comments();
				$comment->post = $post['entityID'];
				$comment->user = $fake->model(User::class);
				$comment->content = $fake->text();

				$comment->save();
			}
		}

		return GREEN . 'Generating comments completed' . RESET . PHP_EOL;
	}


	public function data()
	{
		$this->user();
		$this->post();
		$this->comment();
	}

	public static function helper(): string
	{
		$helperMessage  = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand('post') . "create random posts" . PHP_EOL;
		$helperMessage .= self::printCommand('user') . "create random users" . PHP_EOL;
		$helperMessage .= self::printCommand('comment') . "create random comments" . PHP_EOL;
		$helperMessage .= self::printCommand('data') . "create random data to initial the app for correction" . PHP_EOL;
//		$helperMessage .= CYAN ."     -v --visual" . RESET . "\t\tprint created posts files";

		return $helperMessage;
	}

	private function getImages(): array
	{
		$uploads    = CLIApplication::$ROOT_DIR. 'public/uploads';
		$ignoreFiles= ['.', '..', 'dps', 'index.php'];

		return array_values(array_diff(scandir($uploads), $ignoreFiles));
	}
}
