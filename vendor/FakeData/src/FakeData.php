<?php

namespace vendor\FakeData\src;

class FakeData
{
	/**
	 * @var array|string[]
	 */
	protected array $person = ['_class' => Person::class, 'name', 'fullName', 'firstName', 'lastName', 'username', 'email'];

	/**
	 * @var array|string[]
	 */
	protected array $text = ['_class' => Text::class, 'word', 'sentence', 'text', 'hashtag', 'slugify'];

	/**
	 * @var array|string[]
	 */
	protected array $media = ['_class' => Media::class, 'picture'];

	/**
	 * @var array|string[]
	 */
	protected array $model = ['_class' => Model::class, 'model'];

}
