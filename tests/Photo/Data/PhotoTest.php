<?php declare(strict_types=1);

namespace Test\App\Photo\Data;

use App\Photo\Data\Photo;
use App\Photo\PhotoType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PhotoTest extends TestCase
{
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
		$photo = new Photo($filePath, [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		self::assertSame($expectedKey, $photo->getKey());
		self::assertSame($expectedType, $photo->getType());
	}
}
