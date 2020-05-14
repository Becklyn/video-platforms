<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Validation\Constraint;

use Becklyn\VideoPlatforms\Parser\VideoUrlParser;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class VideoUrlValidator extends ConstraintValidator
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
    public function validate ($value, Constraint $constraint)
    {
        if (null === $value || "" === $value)
        {
            return;
        }

        assert($constraint instanceof VideoUrl);
        $video = $this->parser->parse($value);

        if (null === $video)
        {
            $this->context
                ->buildViolation($constraint->invalidMessage)
                ->addViolation();
        }

        $platforms = $constraint->platforms ?? [];

        if (!empty($platforms) && !\in_array($video->getPlatform(), $platforms, true))
        {
            $this->context
                ->buildViolation($constraint->unsupportedPlatformMessage)
                ->addViolation();
        }
    }


}
