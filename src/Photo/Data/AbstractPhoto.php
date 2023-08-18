<?php declare(strict_types=1);

namespace App\Photo\Data;

use App\Exception\InvalidExifDataException;
use App\Photo\PhotoType;

abstract class AbstractPhoto
{
	protected readonly string $fileName;
	protected readonly string $extension;
	protected readonly \DateTimeInterface $timeCreated;
	protected readonly string $key;
	protected ?PhotoType $type = null;

	/**
	 */
	public function __construct (
		protected readonly string $filePath,
		protected readonly array $exifData,
	)
	{
		$this->fileName = \basename($this->filePath);
		$this->extension = \pathinfo($this->fileName, \PATHINFO_EXTENSION);
		$this->timeCreated = $this->extractTimeCreatedFromExif($exifData);
		$this->key = $this->extractKey($this->fileName);
	}

	/**
	 *
	 */
	private function extractTimeCreatedFromExif (array $exifData) : \DateTimeInterface
	{
		// possible fields to parse with their format
		$possibilities = [
			["CreateDate", "Y:m:d H:i:s"]
		];

		foreach ($possibilities as [$field, $format])
		{
			if (!\array_key_exists($field, $exifData))
			{
				continue;
			}

			$dateTime = \DateTimeImmutable::createFromFormat($format, $exifData[$field]);

			if (false !== $dateTime)
			{
				return $dateTime;
			}
		}

		throw new InvalidExifDataException(\sprintf(
			"Could not find date for file '%s'",
			$this->filePath,
		));
	}

	/**
	 *
	 */
	private function extractKey (string $baseName) : string
	{
		$fileName = \pathinfo($baseName, \PATHINFO_FILENAME);

		$fileName = \preg_replace("~^\d{4}-\d{2}-\d{2} \d{2}-\d{2}-\d{2} -~", "", $fileName);

		return \trim($fileName);
	}

	/**
	 *
	 */
	public function getKey () : string
	{
		return $this->key;
	}

	/**
	 *
	 */
	public function getType () : ?PhotoType
	{
		return $this->type;
	}
}
