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
        yield [new Video("test", "123"), "test://123"];
        yield [new Video("abc", "obgä?_"), "abc://obgä?_"];
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
        yield ["abc://123", new Video("abc", "123")];
        yield ["abc-d://123", new Video("abc-d", "123")];
        yield ["abc_d://123", new Video("abc_d", "123")];
        yield ["abc://obgä?_", new Video("abc", "obgä?_")];
    }

    /**
     * @dataProvider provideUnserialize
     */
    public function testUnserialize (string $actual, Video $expected) : void
    {
        self::assertEquals($expected, Video::fromString($actual));
    }


    /**
     */
    public function provideInvalidUnserialize () : iterable
    {
        yield ["test:test://abc"];
        yield ["test/test://abc"];
        yield ["test test://abc"];
    }

    /**
     * @dataProvider provideInvalidUnserialize
     */
    public function testInvalidUnserialize (string $actual) : void
    {
        $this->expectException(VideoUnserializeException::class);
        Video::fromString($actual);
    }


    /**
     */
    public function provideInvalidCreate () : iterable
    {
        yield ["", "123"];
        yield ["a/b", "213"];
        yield ["a b", "213"];
        yield ["a:b", "213"];
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
}
