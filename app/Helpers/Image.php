<?php

namespace App\Helpers;

use App\Helpers\Exif;
use Jlbelanger\Tapioca\Exceptions\JsonApiException;

class Image
{
	/**
	 * @param  string   $path
	 * @param  integer  $fileType
	 * @param  resource $src
	 * @return array
	 */
	private static function fixOrientation(string $path, int $fileType, $src) : array
	{
		if (empty($src)) {
			return $src;
		}

		$swap = false;

		if (Exif::exists($fileType)) {
			$exif = Exif::get($path);

			if (!empty($exif['Orientation'])) {
				switch ($exif['Orientation']) {
					case 3:
						$src = imagerotate($src, 180, 0);
						break;

					case 6:
						$swap = true;
						$src = imagerotate($src, -90, 0);
						break;

					case 8:
						$swap = true;
						$src = imagerotate($src, 90, 0);
						break;

					default:
						$swap = false;
						break;
				}
			}
		}

		return [
			'image' => $src,
			'swap'  => $swap,
		];
	}

	/**
	 * @param  string  $path
	 * @param  integer $fileType
	 * @return array
	 */
	private static function getImageSource(string $path, int $fileType) : array
	{
		$src = null;

		switch ($fileType) {
			case IMAGETYPE_GIF:
				$src = imagecreatefromgif($path);
				break;

			case IMAGETYPE_JPEG:
				$src = imagecreatefromjpeg($path);
				break;

			case IMAGETYPE_PNG:
				$src = imagecreatefrompng($path);
				break;

			default:
				throw JsonApiException::generate([['title' => 'Invalid file type "' . $fileType . '".', 'status' => '422']], 422);
		}

		return self::fixOrientation($path, $fileType, $src);
	}

	/**
	 * @param  integer $oldWidth
	 * @param  integer $oldHeight
	 * @param  integer $newWidth
	 * @param  string  $srcPath
	 * @param  string  $dstPath
	 * @param  integer $fileType
	 * @return void
	 */
	public static function resize(int $oldWidth, int $oldHeight, int $newWidth, string $srcPath, string $dstPath, int $fileType) : void
	{
		$src = self::getImageSource($srcPath, $fileType);
		if (empty($src) || empty($src['image'])) {
			throw JsonApiException::generate([['title' => 'Could not get image source.', 'status' => '500']], 500);
		}

		if ($src['swap']) {
			$tempWidth = $oldWidth;
			$oldWidth = $oldHeight;
			$oldHeight = $tempWidth;
		}
		$src = $src['image'];

		$ratio = $newWidth / $oldWidth;
		$newHeight = (int) ($oldHeight * $ratio);
		$dstX = 0;
		$dstY = 0;

		$dst = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($dst, $src, $dstX, $dstY, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);

		if ($fileType === IMAGETYPE_PNG) {
			$pngCompression = 1;
			imagepng($dst, $dstPath, $pngCompression);
		} else {
			$jpgCompression = 90;
			imagejpeg($dst, $dstPath, $jpgCompression);
		}

		imagedestroy($src);
		imagedestroy($dst);
	}
}
