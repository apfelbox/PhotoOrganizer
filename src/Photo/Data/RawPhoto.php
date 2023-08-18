<?php declare(strict_types=1);

namespace App\Photo\Data;

use App\Exception\DuplicateRawLinkException;
use App\Photo\PhotoType;

final class RawPhoto extends AbstractPhoto
{
	private ?Photo $exported = null;

	/**
	 * @inheritDoc
	 */
	public function __construct (string $filePath, array $exifData)
	{
		parent::__construct($filePath, $exifData);
		$this->type = PhotoType::RAW;
	}


	/**
	 *
	 */
	public function linkToExported (Photo $photo) : void
	{
		if (null !== $this->exported)
		{
			throw new DuplicateRawLinkException(\sprintf(
				"Can't link raw '%s' to '%s', as it is already linked to '%s'.",
				$this->filePath,
				$photo->filePath,
				$this->exported->filePath,
			));
		}

		$this->exported = $photo;
		$this->exported->setRaw($this);
	}
}
