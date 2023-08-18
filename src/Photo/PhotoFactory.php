<?php declare(strict_types=1);

namespace App\Photo;

use App\Exception\ExifDataExtractionFailedException;
use App\Exception\ExiftoolNotInstalledException;
use App\Exception\PhotoOrganizerExceptionInterface;
use App\Photo\Data\AbstractPhoto;
use App\Photo\Data\Photo;
use App\Photo\Data\RawPhoto;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

final class PhotoFactory
{
	private ?string $exifTool = null;

	/**
	 * @throws PhotoOrganizerExceptionInterface
	 */
	public function create (\SplFileInfo $file) : AbstractPhoto
	{
		$isRaw = "raf" === \strtolower($file->getExtension());

		$exif = $this->extractExifData($file);

		return $isRaw
			? new RawPhoto($file->getPathname(), $exif)
			: new Photo($file->getPathname(), $exif);
	}

	/**
	 *
	 */
	private function extractExifData (\SplFileInfo $file) : array
	{
		$process = new Process([
			$this->getExifTool(),
			"-json",
			$file->getPathname(),
		]);

		try
		{
			$process->mustRun();

			return \json_decode(
				$process->getOutput(),
				true,
				flags: \JSON_THROW_ON_ERROR,
			)[0];
		}
		catch (ProcessFailedException|\JsonException $exception)
		{
			throw new ExifDataExtractionFailedException(
				\sprintf("Failed to extract exif data for file '%s'", $file->getPathname()),
				previous: $exception,
			);
		}
	}


	/**
	 *
	 */
	private function getExifTool () : string
	{
		if (null !== $this->exifTool)
		{
			return $this->exifTool;
		}

		$finder = new ExecutableFinder();
		$executable = $finder->find("exiftool");

		if (null === $executable)
		{
			throw new ExiftoolNotInstalledException();
		}

		return $this->exifTool = $executable;
	}
}
