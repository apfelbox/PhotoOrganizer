<?php declare(strict_types=1);

namespace App\Action;

use App\Photo\Data\RawPhoto;
use App\Photo\Exif\ExifDataExtractor;
use App\Photo\PhotoFactory;
use App\Photo\PhotoLoader;
use App\Storage\Trash;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class CleanRawAction
{
	public function cleanRawFiles (
		SymfonyStyle $io,
		string $inDirectory,
	) : bool
	{

		$loader = new PhotoLoader(new PhotoFactory());
		$filesystem = new Filesystem();

		$exifDataExtractor = new ExifDataExtractor();
		$exifDataCollection = $exifDataExtractor->extractInDir($inDirectory);
		$collection = $loader->loadPhotos($inDirectory, $exifDataCollection);

		$isolatedRaws = $collection->getRawsWithoutExport();

		if (empty($isolatedRaws))
		{
			$io->success("No RAWs to clean found");
			return true;
		}

		$io->writeln("Found RAWs to clean:");
		$io->listing(
			\array_map(
				static fn (RawPhoto $photo) => $photo->getFilePath(),
				$isolatedRaws,
			),
		);

		if ($io->confirm("Should these be removed?", false))
		{
			foreach ($isolatedRaws as $raw)
			{
				$targetPath = Path::join($inDirectory, Trash::TRASH_DIR, $raw->getFilePath());
				$filesystem->mkdir(\dirname($targetPath));

				$filesystem->rename(
					$raw->getFilePath(),
					$targetPath,
				);

				$io->writeln(\sprintf(
					"• Move <fg=yellow>%s</> to _trash",
					$raw->getFilePath(),
				));
			}

			$io->success("All done");
			return true;
		}

		$io->caution("Aborted");
		return false;
	}
}
