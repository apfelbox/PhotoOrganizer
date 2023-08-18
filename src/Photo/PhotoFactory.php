<?php declare(strict_types=1);

namespace App\Photo;

use App\Exception\PhotoOrganizerExceptionInterface;
use App\Photo\Data\AbstractPhoto;
use App\Photo\Data\Photo;
use App\Photo\Data\RawPhoto;
use App\Photo\Exif\ExifDataExtractor;

final class PhotoFactory
{
	/**
	 * @param ExifDataExtractor $exifDataExtractor
	 */
	public function __construct (
		private readonly ExifDataExtractor $exifDataExtractor,
	) {}

	/**
	 * @throws PhotoOrganizerExceptionInterface
	 */
	public function create (
		string $filePath,
		?array $exifData = null,
	) : AbstractPhoto
	{
		if (null === $exifData)
		{
			$exifData = $this->exifDataExtractor->extractExifData($filePath);
		}

		$isRaw = "raf" === \strtolower(\pathinfo($filePath, \PATHINFO_EXTENSION));

		return $isRaw
			? new RawPhoto($filePath, $exifData)
			: new Photo($filePath, $exifData);
	}


}
