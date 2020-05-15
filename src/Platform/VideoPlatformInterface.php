<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Platform;

interface VideoPlatformInterface
{
    /**
     * Returns the unique key for the given platform.
     */
    public static function getKey () : string;


    /**
     * Returns the name of the video platform
     */
    public function getName () : string;


    /**
     * Returns the URL to a page showing the video (on the video platform).
     */
    public function getPreviewUrl (string $id) : string;


    /**
     * Parses the given URL and returns the ID
     */
    public function parseUrl (string $url) : ?string;
}
