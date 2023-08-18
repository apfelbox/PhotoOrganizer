<?php declare(strict_types=1);

namespace App\Photo;

use App\Exception\PhotoOrganizerExceptionInterface;
use App\Photo\Collection\PhotoCollection;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class PhotoLoader
{
	/**
	 * @param PhotoFactory $factory
	 */
	public function __construct (
		private readonly PhotoFactory $photoFactory,
	) {}


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

		$photos = [];

		foreach ($iterator as $file)
		{
			$relativeFilePath = $file->getRelativePathname();

			// skip files in trash
			if ("_trash" === \strtolower(\dirname($relativeFilePath)))
			{
				continue;
			}

			try
			{
				$photos[] = $this->photoFactory->create($relativeFilePath);
			}
			catch (PhotoOrganizerExceptionInterface $exception)
			{
				echo "Found invalid filed '{$file->getPathname()}': {$exception->getMessage()}\n";
			}
		}

		return new PhotoCollection($photos);
	}
}
