<?php declare(strict_types=1);

namespace App\Photo\Exif;

use App\Exception\ExifDataExtractionFailedException;
use App\Exception\ExiftoolNotInstalledException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * @final
 */
class ExifDataExtractor
{
	private ?string $exifTool = null;

	/**
	 *
	 */
	public function extractExifData (string $filePath) : array
	{
		$process = new Process([
			$this->getExifTool(),
			"-json",
			$filePath,
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
				\sprintf("Failed to extract exif data for file '%s'", $filePath),
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
