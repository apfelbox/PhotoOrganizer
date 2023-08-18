<?php declare(strict_types=1);

namespace App\Action;

use App\Photo\Exif\ExifDataExtractor;
use App\Photo\PhotoFactory;
use App\Photo\PhotoLoader;

final class OrganizeAction
{
	public function organizeFiles (string $inDirectory) : void
	{
		$exif = new ExifDataExtractor();
		$factory = new PhotoFactory($exif);
		$loader = new PhotoLoader($factory);

		$collection = $loader->loadPhotos($inDirectory);

		foreach ($collection->getAll() as $file)
		{

		}
		dd($collection);
	}
}
