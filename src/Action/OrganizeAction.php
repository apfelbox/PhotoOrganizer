<?php declare(strict_types=1);

namespace App\Action;

use App\Photo\PhotoLoader;

final class OrganizeAction
{
	public function organizeFiles (string $inDirectory) : void
	{
		$loader = new PhotoLoader();
		$collection = $loader->loadPhotos($inDirectory);
		dd($collection);
	}
}
