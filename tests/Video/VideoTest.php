<?php declare(strict_types=1);

namespace Tests\Becklyn\VideoPlatforms\Video;

use Becklyn\VideoPlatforms\Exception\InvalidVideoDetailsException;
use Becklyn\VideoPlatforms\Exception\VideoUnserializeException;
use Becklyn\VideoPlatforms\Video\Video;
use PHPUnit\Framework\TestCase;

final class VideoTest extends TestCase
{
    /**
     */
    public function provideSerialize () : iterable
    {
        yield [new Video("test", "123"), "test@123"];
        yield [new Video("abc", "obgä?_"), "abc@obgä?_"];
    }


    /**
     * @dataProvider provideSerialize
     */
    public function testSerialize (Video $actual, string $expected) : void
    {
        self::assertSame($expected, $actual->serialize());
    }


    /**
     */
    public function provideUnserialize () : iterable
    {
        yield ["abc@123", new Video("abc", "123")];
        yield ["abc-d@123", new Video("abc-d", "123")];
        yield ["abc_d@123", new Video("abc_d", "123")];
        yield ["abc@obgä?_", new Video("abc", "obgä?_")];
    }

    /**
     * @dataProvider provideUnserialize
     */
    public function testUnserialize (string $actual, Video $expected) : void
    {
        self::assertEquals($expected, Video::unserialize($actual));
    }


    /**
     */
    public function provideInvalidUnserialize () : iterable
    {
        yield ["test@test@abc"];
        yield ["test/test@abc"];
        yield ["test test@abc"];
    }

    /**
     * @dataProvider provideInvalidUnserialize
     */
    public function testInvalidUnserialize (string $actual) : void
    {
        self::assertNull(Video::unserialize($actual));
    }


    /**
     */
    public function provideInvalidCreate () : iterable
    {
        yield ["", "123"];
        yield ["a@b", "213"];
        yield ["a b", "213"];
        yield ["a:b", "213"];
        yield ["a:b", "a@b"];
        yield ["ab", ""];
    }


    /**
     * @dataProvider provideInvalidCreate
     */
    public function testInvalidCreate (string $platform, string $id) : void
    {
        $this->expectException(InvalidVideoDetailsException::class);
        new Video($platform, $id);
    }


    /**
     */
    public function provideArrayCreate () : iterable
    {
        yield "explicit url" => [
            ["platform" => "abc", "id" => "id", "url" => "url"],
            "abc",
            "id",
            "url"
        ];

        yield "auto-generated url" => [
            ["platform" => "abc", "id" => "id"],
            "abc",
            "id",
            "abc@id"
        ];
    }


    /**
     * @dataProvider provideArrayCreate
     */
    public function testArrayCreate (array $value, string $platform, string $id, string $url) : void
    {
        $video = Video::createFromArray($value);
        self::assertSame($platform, $video->getPlatform());
        self::assertSame($id, $video->getId());
        self::assertSame($url, $video->getUrl());
    }


    /**
     */
    public function provideInvalidArrayCreate () : iterable
    {
        yield [[]];
        yield [["platform" => "abc", "id" => "123", "url" => 123]];
    }


    /**
     * @dataProvider provideInvalidArrayCreate
     */
    public function testInvalidArrayCreate (array $value) : void
    {
        $this->expectException(VideoUnserializeException::class);
        Video::createFromArray($value);
    }
}
