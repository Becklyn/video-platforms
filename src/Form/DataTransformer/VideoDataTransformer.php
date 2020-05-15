<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Form\DataTransformer;

use Becklyn\VideoPlatforms\Platform\VideoPlatforms;
use Becklyn\VideoPlatforms\Video\Video;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class VideoDataTransformer implements DataTransformerInterface
{
    private VideoPlatforms $platforms;


    /**
     */
    public function __construct (VideoPlatforms $platforms)
    {
        $this->platforms = $platforms;
    }


    /**
     * @inheritDoc
     */
    public function transform ($value)
    {
        if (null === $value || "" === $value)
        {
            return "";
        }

        if ($value instanceof Video)
        {
            return $value->getUrl();
        }

        throw new TransformationFailedException(\sprintf(
            "Can't transform value of type %s",
            \is_object($value) ? \get_class($value) : \gettype($value)
        ));
    }


    /**
     * @inheritDoc
     */
    public function reverseTransform ($value)
    {
        if (null === $value || "" === $value)
        {
            return null;
        }

        $video = $this->platforms->parseVideoUrl($value);

        if (null === $video)
        {
            throw new TransformationFailedException("Could not parse value as video");
        }

        return $video;
    }
}
