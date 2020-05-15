<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Platform\Implementation;

use Becklyn\VideoPlatforms\Platform\VideoPlatformInterface;

final class VideoPlatformVimeo implements VideoPlatformInterface
{
    /**
     * @inheritDoc
     */
    public static function getKey () : string
    {
        return "vimeo";
    }


    /**
     * @inheritDoc
     */
    public function getName () : string
    {
        return "Vimeo";
    }


    /**
     * @inheritDoc
     */
    public function getPreviewUrl (string $id) : string
    {
        return "https://vimeo.com/{$id}";
    }


    /**
     * @inheritDoc
     */
    public function parseUrl (string $url) : ?string
    {
        if (\preg_match('~^\\d+$~', $url))
        {
            return $url;
        }

        $parsed = \parse_url($url);
        $host = $parsed["host"] ?? null;
        $path = $parsed["path"] ?? "";

        return ("vimeo.com" === $host && \preg_match("~^/(?<id>\\d+)$~", $path, $matches))
            ? $matches["id"]
            : null;
    }
}
