<?php declare(strict_types=1);

namespace App\Photo;

use App\Exception\PhotoOrganizerExceptionInterface;
use App\Photo\Data\AbstractPhoto;
use App\Photo\Data\Photo;
use App\Photo\Data\RawPhoto;

final class PhotoFactory
{
	/**
	 * @throws PhotoOrganizerExceptionInterface
	 */
	public function create (
		string $filePath,
		array $exifData,
	) : AbstractPhoto
	{
		$isRaw = "raf" === \strtolower(\pathinfo($filePath, \PATHINFO_EXTENSION));

		return $isRaw
			? new RawPhoto($filePath, $exifData)
			: new Photo($filePath, $exifData);
	}
}
