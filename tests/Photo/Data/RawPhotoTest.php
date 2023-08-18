<?php declare(strict_types=1);

namespace Tests\App\Photo\Data;

use App\Exception\DuplicateRawLinkException;
use App\Photo\Data\Photo;
use App\Photo\Data\RawPhoto;
use PHPUnit\Framework\TestCase;

class RawPhotoTest extends TestCase
{
	/**
	 *
	 */
	public function testRawLink () : void
	{
		$raw = new RawPhoto("test.raf", [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		$exported = new Photo("test.jpg", [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		self::assertNull($raw->getExportedPhoto());
		self::assertNull($exported->getRaw());

		$raw->linkToExported($exported);
		self::assertSame($exported, $raw->getExportedPhoto());
		self::assertSame($raw, $exported->getRaw());
	}

	/**
	 *
	 */
	public function testDuplicateExportedRawLink () : void
	{
		$this->expectException(DuplicateRawLinkException::class);

		$raw = new RawPhoto("test.raf", [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		$exported = new Photo("test.jpg", [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		$exported2 = new Photo("test2.jpg", [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		$raw->linkToExported($exported);
		$raw->linkToExported($exported2);
	}

	/**
	 *
	 */
	public function testDuplicateRawLink () : void
	{
		$this->expectException(DuplicateRawLinkException::class);

		$raw1 = new RawPhoto("test1.raf", [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		$raw2 = new RawPhoto("test2.raf", [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		$exported = new Photo("test.jpg", [
			"CreateDate" => "2023:08:18 20:00:00",
		]);

		$raw1->linkToExported($exported);
		$raw2->linkToExported($exported);
	}
}
