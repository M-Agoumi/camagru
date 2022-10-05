<?php

namespace Service;

use GdImage;
use mysql_xdevapi\Exception;
use Simfa\Framework\Application;

class ImageProcessor
{
	/**
	 * @var ImageProcessor|null
	 */
	private static ?ImageProcessor $processor = null;

	/**
	 *
	 */
	private function __construct() {}

	/**
	 * @return ImageProcessor
	 */
	public static function getInstance(): ImageProcessor
	{
		if (!self::$processor)
			self::$processor = new ImageProcessor();

		return self::$processor;
	}

	/**
	 * @param string $image
	 * @param int $target_width
	 * @param int $target_height
	 * @param $image_dst
	 * @param string $extension
	 * @return void
	 */
	public function resize(string $image, int $target_width, int $target_height, $image_dst, string $extension = 'jpg'): void
	{
		$thumb = imagecreatetruecolor($target_width, $target_height);
		$source = match ($extension) {
			'jpg' => imagecreatefromjpeg($image),
			'png' => imagecreatefrompng($image),
			'gif' => imagecreatefromgif($image),
			default => throw new Exception('Unknown image type'),
		};

		if ($extension === 'png') {
			imagealphablending( $thumb, false );
			imagesavealpha( $thumb, true );
		}


		list($width, $height) = getimagesize($image);

		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $target_width, $target_height, $width, $height);

		imagepng($thumb, $image_dst, 9);
	}

	/**
	 * @param $image
	 * @param $clientWidth
	 * @param $clientHeight
	 * @return string|bool
	 * @throws \Exception
	 */
	public function prepareMainImage($image, $clientWidth, $clientHeight): string|bool
	{
		/** check if image needs resizing */
		list($width, $height) = getimagesize($image);
		if ($clientWidth == $width && $clientHeight == $height)
			return $image;

		$newImagePath = Application::$ROOT_DIR . '/runtime/tmp/' . uniqid('img_', true) . '.png';

		/** resize image */
		$thumb = imagecreatetruecolor($clientWidth, $clientHeight);
		$imageCode = match (pathinfo($image, PATHINFO_EXTENSION)) {
			'jpg', 'jpeg' => imagecreatefromjpeg($image),
			'png' => imagecreatefrompng($image),
			'gif' => imagecreatefromgif($image),
			default => throw new Exception('Unknown image type'),
		};
		imagealphablending( $thumb, false);
		imagesavealpha( $thumb, true );

		imagecopyresampled($thumb, $imageCode, 0, 0, 0, 0, $clientWidth, $clientHeight, $width, $height);
		$destination = fopen($newImagePath , "w") or throw new \Exception('failed to open cache file for writing');
		imagepng($thumb, $destination , 9);

		/** remove temporary image since we won't use it anymore */
//		unlink($image); // commented while we are testing with the same image we still need it lmao

		return $newImagePath;
	}

	/**
	 * @param $borderImage
	 * @param $sourceWidth
	 * @param $sourceHeight
	 * @return string path to the border that must be used
	 * @throws \Exception
	 */
	public function prepareBorder($borderImage, $sourceWidth, $sourceHeight):string
	{
		$sourceImagePath = Application::$ROOT_DIR . '/public/assets/img/borders/' . $borderImage . '.png';
		/** let's check if the image exists first */
		if (!file_exists($sourceImagePath))
			return false;

		/** get border name without extension and cache file */
		$borderName = pathinfo(Application::$ROOT_DIR . '/public/assets/img/borders/' . $borderImage . '.png', PATHINFO_FILENAME);

		return $this->prepareCommon($borderName, $sourceWidth, $sourceHeight, $sourceImagePath);
	}

	/**
	 * @param $emoteImage
	 * @param $sourceWidth
	 * @param $sourceHeight
	 * @return bool|string
	 * @throws \Exception
	 */
	public function prepareEmote($emoteImage, $sourceWidth, $sourceHeight): bool|string
	{
		$sourceImagePath = Application::$ROOT_DIR . '/public/assets/img/emotes/' . $emoteImage . '.png';
		/** let's check if the image exists first */
		if (!file_exists($sourceImagePath))
			return false;

		/** get emote name without extension and cache file */
		$emoteName = pathinfo(Application::$ROOT_DIR . '/public/assets/img/emotes/' . $emoteImage . '.png', PATHINFO_FILENAME);
		return $this->prepareCommon($emoteName, $sourceWidth, $sourceHeight, $sourceImagePath);
	}

	/**
	 * @param string $emoteName
	 * @param $sourceWidth
	 * @param $sourceHeight
	 * @param string $sourceImagePath
	 * @return string
	 * @throws \Exception
	 */
	private function prepareCommon(
		string $emoteName,
			   $sourceWidth,
			   $sourceHeight,
		string $sourceImagePath
	): string
	{
		$newImageName = $emoteName . '-' . $sourceWidth . '-' . $sourceHeight . '.png';
		$newImagePath = Application::$ROOT_DIR . '/runtime/tmp/img/' . $newImageName;
		/** check if a cached file already exists */
		if (file_exists($newImagePath))
			return Application::$ROOT_DIR . '/runtime/tmp/img/' . $newImageName;

		/** specific border never been processed, let's make it */
		/** check if the image is already in the wanted dimensions and needs nothing to work with */
		list($width, $height) = getimagesize($sourceImagePath);
		if ($sourceWidth == $width && $sourceHeight == $height)
			return $sourceImagePath;

		/** resize to the wanted dimensions and save to cache */
		$thumb = imagecreatetruecolor($sourceWidth, $sourceHeight);
		$borderGd = imagecreatefrompng($sourceImagePath);
		imagealphablending($thumb, false);
		imagesavealpha($thumb, true);

		list($width, $height) = getimagesize($sourceImagePath);
		imagecopyresampled($thumb, $borderGd, 0, 0, 0, 0, $sourceWidth, $sourceHeight, $width, $height);
		$destination = fopen($newImagePath, "w") or throw new \Exception('failed to open cache file for writing');
		imagepng($thumb, $destination, 9);

		return $newImagePath;
	}
}
