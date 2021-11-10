<?php

namespace vendor\FakeData\src;

class FakeData
{
	protected array $person = ['_class' => Person::class, 'name', 'fullName', 'firstName', 'lastName', 'username', 'email'];

	protected array $text = ['_class' => Text::class, 'word', 'sentence', 'text', 'hashtag', 'slugify'];

	protected array $media = ['_class' => Media::class, 'picture'];

}
