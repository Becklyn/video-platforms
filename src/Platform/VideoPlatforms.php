<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Platform;

use Becklyn\VideoPlatforms\Video\Video;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class VideoPlatforms
{
    private ServiceLocator $platforms;


    /**
     */
    public function __construct (ServiceLocator $platforms)
    {
        $this->platforms = $platforms;
    }


    /**
     * Parses the given video URL to
     */
    public function parseVideoUrl (?string $url) : ?Video
    {
        $url = \trim((string) $url);

        if ("" === $url)
        {
            return null;
        }

        if (null !== ($unserialized = $this->tryDirectUnserialize($url)))
        {
            return $unserialized;
        }

        // try every platform to parse the url as ID
        foreach (\array_keys($this->platforms->getProvidedServices()) as $key)
        {
            /** @var VideoPlatformInterface $platform */
            $platform = $this->platforms->get($key);
            $id = $platform->parseUrl($url);

            if (null !== $id)
            {
                return new Video($key, $id, $url);
            }
        }

        return null;
    }


    /**
     * Tries to directly unserialize the url.
     */
    private function tryDirectUnserialize (string $url) : ?Video
    {
        $video = Video::unserialize($url);

        // only return video if video can be unserialized an platform is known.
        return (null !== $video && null !== $this->getPlatform($video->getPlatform()))
            ? $video
            : null;
    }


    /**
     * Fetches the platform by id
     */
    public function getPlatform (string $key) : ?VideoPlatformInterface
    {
        try {
            return $this->platforms->get($key);
        }
        catch (ServiceNotFoundException $exception)
        {
            return null;
        }
    }


    /**
     * Returns the platform name for the given video
     */
    public function getPlatformName (Video $video) : ?string
    {
        $platform = $this->getPlatform($video->getPlatform());

        return null !== $platform
            ? $platform->getName()
            : null;
    }


    /**
     * Returns a preview url for the given video
     */
    public function getPreviewUrl (Video $video) : ?string
    {
        $platform = $this->getPlatform($video->getPlatform());

        return null !== $platform
            ? $platform->getPreviewUrl($video->getId())
            : null;
    }
}
