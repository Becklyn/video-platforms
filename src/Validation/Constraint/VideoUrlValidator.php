<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Validation\Constraint;

use Becklyn\VideoPlatforms\Exception\VideoUnserializeException;
use Becklyn\VideoPlatforms\Video\Video;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class VideoUrlValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate ($value, Constraint $constraint) : void
    {
        if (null === $value || "" === $value)
        {
            return;
        }

        \assert($constraint instanceof VideoUrl);

        if (!\is_array($value))
        {
            $this->invalid($constraint);
            return;
        }

        try
        {
            $video = Video::createFromArray($value);
            \assert($video instanceof Video);

            $platforms = $constraint->platforms ?? [];

            if (!empty($platforms) && !\in_array($video->getPlatform(), $platforms, true))
            {
                $this->context
                    ->buildViolation($constraint->unsupportedPlatformMessage)
                    ->addViolation();
            }
        }
        catch (VideoUnserializeException $exception)
        {
            $this->invalid($constraint);
            return;
        }
    }


    private function invalid (VideoUrl $constraint) : void
    {
        $this->context
            ->buildViolation($constraint->invalidMessage)
            ->addViolation();
    }
}
