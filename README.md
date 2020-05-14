Video Platforms Bundle
======================

Several helpers for integrating video tools in your Symfony application.


Installation
------------

```bash
composer require becklyn/video-platforms
```


Usage
-----


### Parsing an URL

```php
use Becklyn\VideoPlatforms\Parser\VideoUrlParser;


function parse (VideoUrlParser $parser, string $videoUrl)
{
    $video = $parser->parse($videoUrl);
}
```

### Storage

Any video will be stored as normalized array, and can be recreated from it. See below "Usage in entities" for more information.

There is also a simple string-based serialization, although you will lose the initial format:

```php
use Becklyn\VideoPlatforms\Video\Video;

$video = new Video("youtube", "123");

assert("youtube@123" === $video->serialize()); 
```

The internal format is `<platform>@<id>`. That can easily be stored in the database.

To unserialize, just use

```php
use Becklyn\VideoPlatforms\Video\Video;

$serialized = "youtube@123";
$video = Video::unserialize($serialized);

assert("youtube" === $video->getPlatform());
assert("123" === $video->getId());
// will be autogenerate
assert("youtube@123" === $video->getUrl());
```

### Usage in entities

Your entity should look something like this:


```php
use Becklyn\VideoPlatforms\Validation\Constraint\VideoUrl;
use Becklyn\VideoPlatforms\Video\Video;

class MyEntity
{
    /**
     * @ORM\Column(name="video", type="json")
     *
     * @VideoUrl(platforms={"vimeo"})
     * @Assert\NotNull()
     */
     private ?array $video = null;

    /**
     */
    public function getVideo () : ?Video
    {
        return Video::createFromArray($this->video);
    }


    /**
     */
    public function setVideoUrl (?Video $video) : void
    {
        $this->video = null !== $video
            ? $video->toArray()
            : null;
    }
}
```

### Usage in Forms

In your form you should use the `VideoUrlType`:


```php
use Becklyn\VideoPlatforms\Form\Type\VideoUrlType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MyForm extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm (FormBuilderInterface $builder, array $options) : void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add("video", VideoUrlType::class, [
                "label" => "video.label",
                "required" => true,
            ]);
    }
}
```

> **Tip:**
>
> When entering the value in a form field, you can always use the string-serialized version,
> to avoid parsing clashes. So just enter `vimeo@123` for example.

### Validation

You can use the `@VideoUrl()` annotation on any property.

```php
/**
 * @VideoUrl()
 */
```

You can also define which platforms you want to allow. Use the platform key:

```php
/**
 * @VideoUrl(platforms={"vimeo"})
 */
```

### Registering a custom platform

Implement the `VideoUrlParserInterface` and either use autoconfiguration 
or add the DI tag `becklyn.video-platforms.parser`.


### Supported Formats

#### Vimeo

*   `123456789` (plain id, watch out for clashes)
*   `https://vimeo.com/123456789`


#### YouTube

*   `_1234567890` (plain id, watch out for clashes)
*   `https://www.youtube.com/watch?v=_1234567890`
*   `https://www.youtube.com/v/_1234567890`
*   `https://youtu.be/_1234567890`
*   `https://www.youtube.com/oembed?url=http%3A//www.youtube.com/watch?v%3D_1234567890&format=json`
*   `https://youtube.com/embed/_1234567890`
*   `https://www.youtube.com/attribution_link?a=sgsfg&u=%2Fwatch%3Fv%3D_1234567890%26feature%3Dem-uploademail`
