<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation()
 * @Target({"PROPERTY"})
 */
final class VideoUrl extends Constraint
{
    public ?array $platforms = null;
    public string $invalidMessage = "becklyn.video-platforms.invalid";
    public string $unsupportedPlatformMessage = "becklyn.video-platforms.unsupported-platform";


    /**
     * @inheritDoc
     */
    public function validatedBy ()
    {
        return VideoUrlValidator::class;
    }
}
