<?php declare(strict_types=1);

namespace Tests\Becklyn\VideoPlatforms\Platform;

use PHPUnit\Framework\TestCase;

final class VideoPlatformsParseUrlTest extends TestCase
{
    use VideoPlatformsTestTrait;

    /**
     */
    public function provideVariations () : iterable
    {
        yield ["", null];
        yield [null, null];

        // direct format support
        yield "direct format support" => ["youtube@123", "youtube@123"];

        // Valid Vimeo variations
        yield "vimeo plain id" => ["123456789", "vimeo@123456789"];
        yield "vimeo url https" => ["http://vimeo.com/123456789", "vimeo@123456789"];
        yield "vimeo url http" => ["https://vimeo.com/123456789", "vimeo@123456789"];
        yield "vimeo url with query" => ["https://vimeo.com/123456789?test=123", "vimeo@123456789"];
        yield "vimeo url with fragment" => ["https://vimeo.com/123456789#abc", "vimeo@123456789"];
        yield "vimeo url with query + fragment" => ["https://vimeo.com/123456789?test=123#abc", "vimeo@123456789"];

        // Invalid Vimeo variations
        yield "vimeo with parent dir" => ["https://vimeo.com/parent/123456789", null];
        yield "vimeo with sub dir" => ["https://vimeo.com/123456789/sub", null];
        yield "vimeo with parent + sub dir" => ["https://vimeo.com/parent/123456789/sub", null];

        // Valid YouTube variations
        yield "youtube plain id" => ["_1234567890", "youtube@_1234567890"];
        yield "youtube url http" => ["http://www.youtube.com/watch?v=_1234567890", "youtube@_1234567890"];
        yield "youtube url https" => ["https://www.youtube.com/watch?v=_1234567890", "youtube@_1234567890"];
        yield "youtube.com/v" => ["https://www.youtube.com/v/_1234567890?version=3&autohide=1", "youtube@_1234567890"];
        yield "youtu.be https" => ["https://youtu.be/_1234567890", "youtube@_1234567890"];
        yield "youtu.be http" => ["http://youtu.be/_1234567890", "youtube@_1234567890"];
        yield "youtube oembed" => ["https://www.youtube.com/oembed?url=http%3A//www.youtube.com/watch?v%3D_1234567890&format=json", "youtube@_1234567890"];
        yield "youtube embed" => ["https://youtube.com/embed/_1234567890", "youtube@_1234567890"];
        yield "youtube attribution link" => ["https://www.youtube.com/attribution_link?a=sgsfg&u=%2Fwatch%3Fv%3D_1234567890%26feature%3Dem-uploademail", "youtube@_1234567890"];

        // Invalid Youtube Variations
        yield "youtube.com/watch id too long" => ["http://www.youtube.com/watch?v=_12345678901", null];
        yield "youtube.com/watch invalid query param" => ["https://www.youtube.com/watch?a=_1234567890", null];
        yield "www.youtube.com/v id too long" => ["https://www.youtube.com/v/_12345678901?version=3&autohide=1", null];
        yield "www.youtube.com/v sub path" => ["https://www.youtube.com/v/_1234567890/sub?version=3&autohide=1", null];
        yield "youtu.be sub after" => ["https://youtu.be/_1234567890/sub", null];
        yield "youtu.be sub before" => ["https://youtu.be/sub/_1234567890", null];
        yield "youtu.be id too long" => ["https://youtu.be/_12345678901", null];
        yield "youtube oembed id too long" => ["https://www.youtube.com/oembed?url=http%3A//www.youtube.com/watch?v%3D_12345678901&format=json", null];
        yield "youtube oembed invalid query parameter" => ["https://www.youtube.com/oembed?url=http%3A//www.youtube.com/watch?a%3D_1234567890&format=json", null];
        yield "youtube embed id too long" => ["https://youtube.com/embed/_12345678901", null];
        yield "youtube embed sub after" => ["https://youtube.com/embed/_1234567890/sub", null];
        yield "youtube embed sub before" => ["https://youtube.com/embed/sub/_1234567890", null];
        yield "youtube attribution id too long" => ["https://www.youtube.com/attribution_link?a=sgsfg&u=%2Fwatch%3Fv%3D_12345678901%26feature%3Dem-uploademail", null];
        yield "youtube attribution missing parameter" => ["https://www.youtube.com/attribution_link?a=sgsfg&u=%2Fwatch%3Fa%3D_1234567890%26feature%3Dem-uploademail", null];
    }


    /**
     * @dataProvider provideVariations
     */
    public function testVariations (?string $videoUrl, ?string $expected) : void
    {
        $platforms = $this->createVideoPlatforms();

        $video = $platforms->parseVideoUrl($videoUrl);
        $actual = null !== $video
            ? $video->serialize()
            : null;

        self::assertSame($expected, $actual);
    }
}
