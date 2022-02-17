<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms;

use Becklyn\RadBundles\Bundle\BundleExtension;
use Becklyn\VideoPlatforms\Platform\VideoPlatformInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BecklynVideoPlatformsBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getContainerExtension () : ?ExtensionInterface
    {
        return new BundleExtension($this);
    }


    /**
     * @inheritDoc
     */
    public function build (ContainerBuilder $container) : void
    {
        $container->registerForAutoconfiguration(VideoPlatformInterface::class)
            ->addTag("becklyn.video-platform");
    }
}
