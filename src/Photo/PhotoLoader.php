<?php declare(strict_types=1);

namespace App\Photo;

use App\Exception\PhotoOrganizerExceptionInterface;
use App\Photo\Collection\PhotoCollection;
use App\Photo\Exif\ExifDataCollection;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

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
	public function loadPhotos (
		string $directory,
		ExifDataCollection $exifDataCollection,
		?SymfonyStyle $io = null,
	) : PhotoCollection
	{
		$iterator = Finder::create()
			->in($directory)
			->ignoreUnreadableDirs()
			->depth("<= 1")
			->files();

		$io?->writeln("• Detecting files in directory...");
		$progress = $io?->createProgressBar(\iterator_count($iterator));
		$photos = [];

		foreach ($iterator as $file)
		{
			$progress?->advance();
			$relativeFilePath = $file->getRelativePathname();

			// skip files in trash
			if ("_trash" === \strtolower(\dirname($relativeFilePath)))
			{
				continue;
			}

			try
			{
				$exifData = $exifDataCollection->getData($file->getPathname());
				$photos[] = $this->photoFactory->create($relativeFilePath, $exifData);
			}
			catch (PhotoOrganizerExceptionInterface $exception)
			{
				echo "Found invalid filed '{$file->getPathname()}': {$exception->getMessage()}\n";
			}
		}

		$progress?->finish();
		$io?->newLine();

		return new PhotoCollection($photos);
	}
}
