<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Parser\Platform;

use Becklyn\VideoPlatforms\Parser\VideoUrlParserInterface;
use Becklyn\VideoPlatforms\Video\Video;

final class VideoUrlParserVimeo implements VideoUrlParserInterface
{
    private const KEY = "vimeo";

    /**
     * @inheritDoc
     */
    public function parse (string $videoUrl) : ?Video
    {
        if (\preg_match('~^\\d+$~', $videoUrl))
        {
            return new Video(self::KEY, $videoUrl);
        }

        $parsed = \parse_url($videoUrl);
        $host = $parsed["host"] ?? null;
        $path = $parsed["path"] ?? "";

        return ("vimeo.com" === $host && \preg_match("~^/(?<id>\\d+)$~", $path, $matches))
            ? new Video(self::KEY, $matches["id"])
            : null;
    }
}
