<?php


namespace models;


use core\Db\DbModel;

class Post extends DbModel
{

	public ?int $id = null;
	public ?string $title = null;
	public ?string $comment = null;
	public ?string $picture = null;
	public ?string $slug = null;
	public ?int $status = null;
	public ?int $author = null;


	/**
	 * @return string
	 */
	public function tableName(): string
	{
		return 'posts';
	}

	/**
	 * @return array
	 */
	public function attributes(): array
	{
		return ['title', 'comment', 'picture', 'slug', 'status', 'author'];
	}

	/**
	 * @return string
	 */
	public function primaryKey(): string
	{
		return 'id';
	}

	/**
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'picture' => [self::RULE_REQUIRED],
		];
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function hashtag(string $comment)
	{
		$hashtags = '';
		$tags = preg_match_all("/#(\w+)/", $comment, $m);

		$m = array_map('strtolower', $m[1]);

		foreach ($m as $tag) {
			$hashtags .= "<a href='/hashtag/$tag'>$tag</a>,\n";
		}
		return rtrim($hashtags, ',');
	}

	public function highlightHashtag(string $comment){
		/** we do it the old fashion way C way */
		$i = 0; /** our increment  */
		$newComment = $comment; /** where we save our changes */


		while ($i < strlen($comment)) {
			if ($comment[$i] === '#') {
				$tmp = $i + 1;
				$len = $this->getHashtag($comment, $tmp + 1);
				$hash = $this->printRange($comment, $tmp, $len);
				$hashLink = "<a href='/hashtag/" . strtolower($hash) . "'>#" . $hash . "</a>";
				$newComment = str_replace("#" . $hash, $hashLink, $comment);
				$comment = $newComment;
				$i += strlen($hashLink);
			}
			$i++;
		}
		return $newComment;
	}

	private function getHashtag(string $comment, int $i): int
	{
		while ($i < strlen($comment)) {
			if (
				($comment[$i] <= 'z' && $comment[$i] >= 'a') ||
				($comment[$i] <= 'Z' && $comment[$i] >= 'A') ||
				($comment[$i] >= '0' && $comment[$i] <= '9')
			)
				$i++;
			else
				return $i;
		}

		return $i;
	}

	private function printRange(string $comment, int $from, int $to): string
	{
		$hashtag = '';
		while ($from < $to) {
			$hashtag .= $comment[$from++];
		}

		return $hashtag;
	}
	/**
	 * ($comment[$i] > 'Z' && $comment[$i] < 'A') ||
	($comment[$i] > '0' && $comment[$i] < '9')
	 */
}