<?php declare(strict_types=1);

namespace App\Photo;

use App\Exception\PhotoOrganizerExceptionInterface;
use App\Photo\Data\AbstractPhoto;
use App\Photo\Data\Photo;
use App\Photo\Data\RawPhoto;

final class PhotoFactory
{
	private const RAW_FILE_EXTENSIONS = [
		// Fuji
		"raf",
	];

	/**
	 * @throws PhotoOrganizerExceptionInterface
	 */
	public function create (
		string $filePath,
		array $exifData,
	) : AbstractPhoto
	{
		$normalizedFileExtension = \strtolower(\pathinfo($filePath, \PATHINFO_EXTENSION));

		return \in_array($normalizedFileExtension, self::RAW_FILE_EXTENSIONS)
			? new RawPhoto($filePath, $exifData)
			: new Photo($filePath, $exifData);
	}
}
