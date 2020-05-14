<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Form\DataTransformer;

use Becklyn\VideoPlatforms\Parser\VideoUrlParser;
use Becklyn\VideoPlatforms\Video\Video;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class VideoDataTransformer implements DataTransformerInterface
{
    private VideoUrlParser $parser;


    /**
     */
    public function __construct (VideoUrlParser $parser)
    {
        $this->parser = $parser;
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

        $video = $this->parser->parse($value);

        if (null === $video)
        {
            throw new TransformationFailedException("Could not parse value as video");
        }

        return $video;
    }
}
