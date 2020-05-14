<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Parser\Platform;

use Becklyn\VideoPlatforms\Parser\VideoUrlParserInterface;
use Becklyn\VideoPlatforms\Video\Video;

final class VideoUrlParserYoutube implements VideoUrlParserInterface
{
    private const KEY = "youtube";

    /**
     * @inheritDoc
     */
    public function parse (string $videoUrl) : ?Video
    {
        // required as we sometimes parse recursively
        if ("" === $videoUrl)
        {
            return null;
        }

        if ($this->isValidId($videoUrl))
        {
            return new Video(self::KEY, $videoUrl, $videoUrl);
        }

        $parsed = \parse_url($videoUrl);
        $host = $parsed["host"] ?? null;
        $path = $parsed["path"] ?? "";
        \parse_str($parsed["query"] ?? "", $query);

        if ("www.youtube.com" === $host || "youtube.com" === $host)
        {
            if ("/watch" === $path)
            {
                return $this->tryCreate($query["v"] ?? "", $videoUrl);
            }

            if (\preg_match('~^/(v|embed)/(?<id>.*?)$~', $path, $match))
            {
                return $this->tryCreate($match["id"], $videoUrl);
            }

            if ("/oembed" === $path)
            {
                $subVideo = $this->parse($query["url"] ?? "");

                return null !== $subVideo
                    // keep top level url
                    ? new Video($subVideo->getPlatform(), $subVideo->getId(), $videoUrl)
                    : null;
            }

            if ("/attribution_link" === $path)
            {
                $subUrl = \parse_url($query["u"] ?? "");
                 \parse_str($subUrl["query"] ?? "", $subQuery);

                return ("/watch" === ($subUrl["path"] ?? ""))
                    ? $this->tryCreate($subQuery["v"] ?? "", $videoUrl)
                    : null;
            }

            return null;
        }

        if ("youtu.be" === $host)
        {
            if (\preg_match('~^/(?<id>.*?)$~', $path, $match))
            {
                return $this->tryCreate($match["id"], $videoUrl);
            }
        }

        return null;
    }


    /**
     * Checks whether the given ID is valid
     */
    private function isValidId (string $id) : bool
    {
        // my not contain a "/"
        if (false !== \strpos($id, "/"))
        {
            return false;
        }

        return 1 === \preg_match('~^[\\w-]{11}$~', $id);
    }


    /**
     * Tries to create a video if the ID is valid, returns null otherwise
     */
    private function tryCreate (string $id, string $videoUrl) : ?Video
    {
        return $this->isValidId($id)
            ? new Video(self::KEY, $id, $videoUrl)
            : null;
    }
}
