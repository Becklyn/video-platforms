<?php declare(strict_types=1);

namespace Tests\Becklyn\VideoPlatforms\Platform;

use Becklyn\VideoPlatforms\Platform\Implementation\VideoPlatformVimeo;
use Becklyn\VideoPlatforms\Platform\Implementation\VideoPlatformYouTube;
use Becklyn\VideoPlatforms\Platform\VideoPlatforms;
use Symfony\Component\DependencyInjection\ServiceLocator;

trait VideoPlatformsTestTrait
{
    /**
     * Returns a fully built video platforms implementation
     */
    private function createVideoPlatforms () : VideoPlatforms
    {
        return new VideoPlatforms(
            new ServiceLocator([
                VideoPlatformVimeo::getKey() => fn() => new VideoPlatformVimeo(),
                VideoPlatformYouTube::getKey() => fn() => new VideoPlatformYouTube(),
            ])
        );
    }
}
