<?php declare(strict_types=1);

namespace App\Photo\Collection;

use App\Photo\Data\AbstractPhoto;
use App\Photo\Data\Photo;
use App\Photo\Data\RawPhoto;

final class PhotoCollection
{
	/** @var Photo[] */
	private array $photos = [];
	/** @var RawPhoto[] */
	private array $raws = [];

	/**
	 * @param AbstractPhoto[] $photos
	 */
	public function __construct (
		array $photos,
	)
	{
		foreach ($photos as $photo)
		{
			if ($photo instanceof RawPhoto)
			{
				$this->raws[$photo->getKey()] = $photo;
			}
			else
			{
				$this->photos[$photo->getKey()] = $photo;
			}
		}

		$this->linkRaws();
	}

	/**
	 *
	 */
	private function linkRaws () : void
	{
		foreach ($this->raws as $raw)
		{
			$exportedPhoto = $this->photos[$raw->getKey()] ?? null;

			if (null !== $exportedPhoto)
			{
				$raw->linkToExported($exportedPhoto);
			}
		}
	}

	/**
	 * @return array<AbstractPhoto>
	 */
	public function getAll () : array
	{
		$result = \array_values($this->raws);

		foreach ($this->photos as $photo)
		{
			$result[] = $photo;
		}

		return $result;
	}
}
