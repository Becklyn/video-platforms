<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Form\Type;

use Becklyn\VideoPlatforms\Form\DataTransformer\VideoDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class VideoUrlType extends AbstractType
{
    private VideoDataTransformer $dataTransformer;


    /**
     */
    public function __construct (VideoDataTransformer $dataTransformer)
    {
        $this->dataTransformer = $dataTransformer;
    }

    /**
     * @inheritDoc
     */
    public function buildForm (FormBuilderInterface $builder, array $options) : void
    {
        $builder->addModelTransformer($this->dataTransformer);
    }


    /**
     * @inheritDoc
     */
    public function getParent ()
    {
        return TextType::class;
    }
}
