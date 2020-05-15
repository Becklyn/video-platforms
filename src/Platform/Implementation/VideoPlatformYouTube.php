<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Platform\Implementation;

use Becklyn\VideoPlatforms\Platform\VideoPlatformInterface;

final class VideoPlatformYouTube implements VideoPlatformInterface
{
    /**
     * @inheritDoc
     */
    public static function getKey () : string
    {
        return "youtube";
    }


    /**
     * @inheritDoc
     */
    public function getName () : string
    {
        return "YouTube";
    }


    /**
     * @inheritDoc
     */
    public function getPreviewUrl (string $id) : string
    {
        return "https://www.youtube.com/watch?v={$id}";
    }


    /**
     * @inheritDoc
     */
    public function parseUrl (string $url) : ?string
    {
        // required as we sometimes parse recursively
        if ("" === $url)
        {
            return null;
        }

        if ($this->isValidId($url))
        {
            return $url;
        }

        $parsed = \parse_url($url);
        $host = $parsed["host"] ?? null;
        $path = $parsed["path"] ?? "";
        \parse_str($parsed["query"] ?? "", $query);

        if ("www.youtube.com" === $host || "youtube.com" === $host)
        {
            if ("/watch" === $path)
            {
                return $this->tryCreate($query["v"] ?? "");
            }

            if (\preg_match('~^/(v|embed)/(?<id>.*?)$~', $path, $match))
            {
                return $this->tryCreate($match["id"]);
            }

            if ("/oembed" === $path)
            {
                return $this->parseUrl($query["url"] ?? "");
            }

            if ("/attribution_link" === $path)
            {
                $subUrl = \parse_url($query["u"] ?? "");
                \parse_str($subUrl["query"] ?? "", $subQuery);

                return ("/watch" === ($subUrl["path"] ?? ""))
                    ? $this->tryCreate($subQuery["v"] ?? "")
                    : null;
            }

            return null;
        }

        if ("youtu.be" === $host)
        {
            if (\preg_match('~^/(?<id>.*?)$~', $path, $match))
            {
                return $this->tryCreate($match["id"]);
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
    private function tryCreate (string $id) : ?string
    {
        return $this->isValidId($id) ? $id : null;
    }
}
