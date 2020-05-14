<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms;

use Becklyn\RadBundle\Bundle\BundleExtension;
use Becklyn\VideoPlatforms\Parser\VideoUrlParserInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BecklynVideoPlatformsBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getContainerExtension ()
    {
        return new BundleExtension($this);
    }


    /**
     * @inheritDoc
     */
    public function build (ContainerBuilder $container) : void
    {
        $container->registerForAutoconfiguration(VideoUrlParserInterface::class)
            ->addTag("becklyn.video-platforms.parser");
    }


    /**
     * @inheritDoc
     */
    public function getPath ()
    {
        return \dirname(__DIR__);
    }
}
