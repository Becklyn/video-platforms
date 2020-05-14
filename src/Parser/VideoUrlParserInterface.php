<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Parser;

use Becklyn\VideoPlatforms\Video\Video;

interface VideoUrlParserInterface
{
    /**
     * Parses the given video URL
     */
    public function parse (string $videoUrl) : ?Video;
}
