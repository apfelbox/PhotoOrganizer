<?php declare(strict_types=1);

namespace App\Photo\Data;

use App\Exception\DuplicateRawLinkException;

final class Photo extends AbstractPhoto
{
	private ?RawPhoto $raw = null;

	/**
	 * This is called from {@see RawPhoto::linkToExported()}
	 *
	 * @internal
	 */
	public function setRaw (RawPhoto $raw) : void
	{
		if (null !== $this->raw)
		{
			throw new DuplicateRawLinkException(\sprintf(
				"File %s can't be linked to raw file, as it already is linked to %s",
				$this->filePath,
				$this->raw->filePath,
			));
		}

		$this->raw = $raw;
	}
}
