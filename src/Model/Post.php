<?php


namespace Model;


use Simfa\Framework\Db\DbModel;

/**
 * @method getPicture():string
 * @method getTitle():string
 * @method getComment():string
 * @method getSlug():string
 * @method getStatus():int
 * @method getAuthor(): Model\User
 */
class Post extends DbModel
{

	public ?int $entityID = null;
	public ?string $title = null;
	public ?string $comment = null;
	public ?string $picture = null;
	public ?string $slug = null;
	public ?int $status = null;
	public ?User $author = null;

	/**
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'picture' => [self::RULE_REQUIRED],
		];
	}

	public function relationships(): array
	{
		return ['author' => User::class];
	}

	/** extract hashtags from the comment and return them as array
	 * @param string $comment
	 * @return string]
	 */
	public function hashtag(string $comment): string
	{
		$hashtags = '';
		$tags = preg_match_all("/#(\w+)/", $comment, $m);

		$m = array_map('strtolower', $m[1]);

		foreach ($m as $tag) {
			$hashtags .= "<a href='/hashtag/$tag'>$tag</a>,\n";
		}

		return rtrim($hashtags, ',');
	}

	/**
	 * @param string $comment
	 * @return array|mixed|string|string[]
	 */
	public function highlightHashtag(string $comment){
		/** we do it the old fashion way, C way */
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

	/**
	 * @param string $comment
	 * @param int $i
	 * @return int
	 */
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

	/**
	 * @param string $comment
	 * @param int $from
	 * @param int $to
	 * @return string
	 */
	private function printRange(string $comment, int $from, int $to): string
	{
		$hashtag = '';
		while ($from < $to) {
			$hashtag .= $comment[$from++];
		}

		return $hashtag;
	}
}
