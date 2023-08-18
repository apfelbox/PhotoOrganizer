<?php declare(strict_types=1);

namespace Tests\App\Photo;

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
			__DIR__ . "/../fixtures/1",
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
		$exif = $this->createMock(ExifDataExtractor::class);

		$exif->method("extractExifData")
			->willReturn([
				"CreateDate" => "2023:08:18 20:00:00",
			]);

		$factory = new PhotoFactory($exif);
		$loader = new PhotoLoader($factory);
		$collection = $loader->loadPhotos($baseDirectory);

		$filePaths = [];
		foreach ($collection->getAll() as $photo)
		{
			$filePaths[] = $photo->getFilePath();
		}

		self::assertEqualsCanonicalizing($expectedFilePaths, $filePaths);
	}
}
