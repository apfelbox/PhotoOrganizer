<?php declare(strict_types=1);

namespace Tests\App\Photo;

use App\Photo\Collection\PhotoCollection;
use App\Photo\Data\Photo;
use App\Photo\Data\RawPhoto;
use App\Photo\Exif\ExifDataExtractor;
use App\Photo\PhotoFactory;
use App\Photo\PhotoLoader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PhotoLoaderTest extends TestCase
{
	/**
	 *
	 */
	public static function provideLoadPhotos () : iterable
	{
		yield [
			__DIR__ . "/../fixtures/finding",
			[
				"a.png",
				"test/d.png",
				"_RAW/b.png",
			],
		];
	}


	/**
	 *
	 */
	#[DataProvider("provideLoadPhotos")]
	public function testLoadPhotos (
		string $baseDirectory,
		array $expectedFilePaths,
	) : void
	{
		$collection = $this->fetchCollectionForDirectory($baseDirectory);

		$filePaths = [];
		foreach ($collection->getAll() as $photo)
		{
			$filePaths[] = $photo->getFilePath();
		}

		self::assertEqualsCanonicalizing($expectedFilePaths, $filePaths);
	}


	public function testRawLinking () : void
	{
		$collection = $this->fetchCollectionForDirectory(__DIR__ . "/../fixtures/raw-linking");
		$files = $collection->getAll();

		self::assertCount(2, $files);

		if ($files[0] instanceof RawPhoto)
		{
			[$raw, $export] = $files;
		}
		else
		{
			[$export, $raw] = $files;
		}

		self::assertInstanceOf(RawPhoto::class, $raw);
		self::assertInstanceOf(Photo::class, $export);

		self::assertSame($raw, $export->getRaw());
		self::assertSame($export, $raw->getExportedPhoto());
	}


	/**
	 */
	private function fetchCollectionForDirectory (string $directoryPath) : PhotoCollection
	{
		$exif = $this->createMock(ExifDataExtractor::class);

		$exif->method("extractExifData")
			->willReturn([
				"CreateDate" => "2023:08:18 20:00:00",
			]);

		$factory = new PhotoFactory($exif);
		$loader = new PhotoLoader($factory);

		return $loader->loadPhotos($directoryPath);
	}
}
