<?php declare(strict_types=1);

namespace Test\App\Photo\Data;

use App\Photo\PhotoFactory;
use App\Photo\PhotoType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AbstractPhotoTest extends TestCase
{
	/**
	 *
	 */
	public static function provideStaticParse () : iterable
	{
		yield [
			"test.jpg",
			"test",
			null,
		];

		yield [
			"a.jpg",
			"a",
			null,
		];

		yield [
			" a .jpg",
			"a",
			null,
		];

		yield [
			"test/a.jpg",
			"a",
			null,
		];

		yield [
			"2023-08-18 20-00-00 - abc def.jpg",
			"abc def",
			null,
		];

		yield [
			"test/2023-08-18 20-00-00 - abc def.jpg",
			"abc def",
			null,
		];

		yield [
			"test/2023-08-18 20-00-00 -    abc def  .jpg",
			"abc def",
			null,
		];

		yield [
			"test.raf",
			"test",
			PhotoType::RAW,
		];

		yield [
			"test.RAF",
			"test",
			PhotoType::RAW,
		];
	}

	/**
	 *
	 */
	#[DataProvider("provideStaticParse")]
	public function testValidParse (
		string $filePath,
		string $expectedKey,
		?PhotoType $expectedType,
	) : void
	{
		$factory = new PhotoFactory();
		$photo = $factory->createWithExifData($filePath, [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		self::assertSame($expectedKey, $photo->getKey());
		self::assertSame($expectedType, $photo->getType());
	}

	/**
	 *
	 */
	public static function provideTargetPath () : iterable
	{
		$date = "2023-08-18 20-00-00";

		yield [
			"test.raf",
			"_RAW/{$date} - test.raf",
		];

		yield [
			"test.RAF",
			"_RAW/{$date} - test.raf",
		];

		yield [
			"_RAW/test.RAF",
			"_RAW/{$date} - test.raf",
		];

		yield [
			"_RAW/{$date} - test.RAF",
			"_RAW/{$date} - test.raf",
		];

		yield [
			"test.jpg",
			"{$date} - test.jpg",
		];

		yield [
			"test.JPG",
			"{$date} - test.jpg",
		];

		yield [
			"test.heic",
			"{$date} - test.heic",
		];

		yield [
			"test.HEIC",
			"{$date} - test.heic",
		];
	}

	/**
	 *
	 */
	#[DataProvider("provideTargetPath")]
	public function testTargetPath (
		string $filePath,
		string $expectedTargetPath,
	) : void
	{
		$factory = new PhotoFactory();
		$photo = $factory->createWithExifData($filePath, [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		self::assertSame($expectedTargetPath, $photo->getTargetPath());
	}
}
