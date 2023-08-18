<?php declare(strict_types=1);

namespace App\Photo;

use App\Exception\PhotoOrganizerExceptionInterface;
use App\Photo\Data\Photo;
use App\Photo\Collection\PhotoCollection;
use App\Photo\Data\RawPhoto;
use Symfony\Component\Finder\Finder;

final class PhotoLoader
{
	/**
	 *
	 */
	public function loadPhotos (string $directory) : PhotoCollection
	{
		$iterator = Finder::create()
			->in($directory)
			->ignoreUnreadableDirs()
			->depth("<= 1")
			->files();

		$factory = new PhotoFactory();

		$photos = [];

		foreach ($iterator as $file)
		{
			try
			{
				$photos[] = $factory->create($file);
			}
			catch (PhotoOrganizerExceptionInterface $exception)
			{
				echo "Found invalid filed '{$file->getPathname()}': {$exception->getMessage()}\n";
			}
		}

		return new PhotoCollection($photos);
	}
}
