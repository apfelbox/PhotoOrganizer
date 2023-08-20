<?php declare(strict_types=1);

namespace App\Photo\Exif;

use App\Exception\ExifDataExtractionFailedException;

/**
 * @final
 */
class ExifDataCollection
{
	private readonly array $indexedData;

	/**
	 */
	public function __construct (array $data)
	{
		$indexed = [];

		foreach ($data as $entry)
		{
			$indexed[$entry["SourceFile"]] = $entry;
		}

		$this->indexedData = $indexed;
	}


	/**
	 */
	public function getData (string $filePath) : array
	{
		if (!\array_key_exists($filePath, $this->indexedData))
		{
			throw new ExifDataExtractionFailedException(\sprintf(
				"Did not find exif data for file '%s'",
				$filePath,
			));
		}

		return $this->indexedData[$filePath];
	}
}
