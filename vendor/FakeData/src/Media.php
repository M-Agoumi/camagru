<?php

namespace vendor\FakeData\src;

class Media
{
	/** generate random image based on keyword
	 * @param $path string [Required] path where to store the image
	 * @param string|null $keyword
	 * @throws \Exception
	 */
	public function picture(string $path, ?string $keyword = ''): string
	{
		/** generate image name */
		$imageFile = $keyword . bin2hex(random_bytes(14)) . ".jpg";

		copy('https://loremflickr.com/650/550/' . $keyword, $path . '/' . $imageFile);

		return $imageFile;
	}
}
