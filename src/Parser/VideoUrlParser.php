<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Parser;

use Becklyn\VideoPlatforms\Video\Video;

final class VideoUrlParser
{
    /** @var VideoUrlParserInterface[]|iterable */
    private iterable $parsers;

    /**
     */
    public function __construct (iterable $parsers)
    {
        $this->parsers = $parsers;
    }


    /**
     * Parses the given video url
     */
    public function parse (?string $videoUrl) : ?Video
    {
        $videoUrl = \trim((string) $videoUrl);

        if ("" === $videoUrl)
        {
            return null;
        }

        foreach ($this->parsers as $parser)
        {
            $video = $parser->parse($videoUrl);

            if (null !== $video)
            {
                return $video;
            }
        }

        return null;
    }
}
