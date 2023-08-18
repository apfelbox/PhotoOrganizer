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

	}
}
