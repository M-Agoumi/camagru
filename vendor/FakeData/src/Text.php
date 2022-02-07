<?php

namespace FakeData\src;

class Text
{
	public function word(): string
	{
		$file = fopen(dirname(__DIR__) . '/resource/words.csv', 'r');
		$words = [];
		while (($line = fgetcsv($file)) !== FALSE) {
			$words[] = $line[0];
		}
		fclose($file);

		return Generator::random($words);
	}

	/**
	 * @throws \Exception
	 */
	public function sentence($wordsNumber = null): string
	{
		$wordsNumber = $wordsNumber == null ? random_int(2, 5) : $wordsNumber;
		$word = $this->word();
		for ($i = 1; $i < $wordsNumber; $i++)
			$word .= ' ' . $this->word();

		return $word;
	}

	/**
	 * @throws \Exception
	 */
	public function text($min = 5, $max = null): string
	{
		if ($max == null)
			return $this->sentence($min);

		$words = random_int($min, $max);

		return $this->sentence($words);
	}

	/**
	 * @throws \Exception
	 */
	public function hashtag($number = null): string
	{
		$number = $number == null ? random_int(1, 20) : $number;

		$file = fopen(dirname(__DIR__) . '/resource/hashtags.csv', 'r');
		$words = [];
		while (($line = fgetcsv($file)) !== FALSE) {
			$words[] = $line[0];
		}
		fclose($file);

		$hashtags = '#' . Generator::random($words);

		for ($i = 1; $i < $number; $i++)
		{
			$hashtags .= ' #' . Generator::random($words);
		}

		return $hashtags;
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

	/**
	 * @param int $min
	 * @param int $max
	 * @return int
	 * @throws \Exception
	 */
	public function number(int $min = -2147483648, int $max = 2147483647): int
	{
		return random_int($min, $max);
	}
}
