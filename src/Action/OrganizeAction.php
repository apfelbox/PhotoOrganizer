<?php declare(strict_types=1);

namespace App\Action;

use App\Photo\Exif\ExifDataExtractor;
use App\Photo\PhotoFactory;
use App\Photo\PhotoLoader;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final class OrganizeAction
{
	public function organizeFiles (
		SymfonyStyle $io,
		string $inDirectory,
	) : void
	{
		$exif = new ExifDataExtractor();
		$factory = new PhotoFactory($exif);
		$loader = new PhotoLoader($factory);
		$filesystem = new Filesystem();

		$collection = $loader->loadPhotos($inDirectory);

		foreach ($collection->getAll() as $file)
		{
			$io->write("• {$file->getFilePath()} ... ");

			if ($file->getFilePath() !== $file->getTargetPath())
			{
				$fullTargetPath = Path::join($inDirectory, $file->getTargetPath());

				// ensure dir exists
				$filesystem->mkdir(\dirname($fullTargetPath));

				$filesystem->rename(
					Path::join($inDirectory, $file->getFilePath()),
					$fullTargetPath,
				);
				$io->writeln("<fg=green>moved</>");
			}
			else
			{
				$io->writeln("<fg=gray>skipped</>");
			}
		}
	}
}
