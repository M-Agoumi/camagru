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
 * @method setPicture(mixed $get)
 * @method setTitle(string $string)
 */
class Post extends DbModel
{

	public ?int $entityID = null;
	public ?string $title = null;
	public ?string $comment = null;
	public ?string $picture = null;
	public ?string $slug = null;
	public ?int $status = null;
	protected int $spoiler = 0;
	public ?User $author = null;


	/**
	 * @var array $protected properties from public shows like api
	 */
	protected static array $protected = ['entityID'];

	/**
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'picture' => [self::RULE_REQUIRED],
			'spoiler' => [self::RULE_REQUIRED]
		];
	}

	/**
	 * @return string[]
	 */
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
	 * @return array|string
	 */
	public function highlightHashtag(string $comment): array|string
	{
		/** we do it the old fashion way, C way */
		$i = 0; /** our increment  */
		$newComment = htmlspecialchars($comment, ENT_QUOTES, false); /** where we save our changes */
		$commentLen = strlen($comment);

		while ($i < $commentLen) {
			if ($comment[$i] == '#') {
				$hashtagLen = $this->getHashtag($comment, $i + 1) - $i - 1;
				$hashtag    = substr($comment, $i+1, $hashtagLen);
				$link       = ' <a href="' . route('hashtag', $hashtag) . '">&#35;' . $hashtag . '</a>';
				$newComment = $this->str_replace_first('#' . $hashtag, $link, $newComment);
			}
			$i++;
		}

		return $newComment;
	}

	/**
	 * @param $search
	 * @param $replace
	 * @param $subject
	 * @return array|string
	 */
	private function str_replace_first($search, $replace, $subject): array|string
	{
		$pos = strpos($subject, $search);
		if ($pos !== false) {
			return substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
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
				($comment[$i] >= '0' && $comment[$i] <= '9') ||
				($comment[$i] == '-' || $comment[$i] == '_')
			)
				$i++;
			else
				return $i;
		}

		return $i;
	}

}
