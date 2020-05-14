Video Platforms Bundle
======================

Several helpers for integrating video tools in your Symfony application.


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

Any video can be stored by serializing it:

```php
$video = new Video("youtube", "123");

assert("youtube@123" === $video->serialize()); 
```

The internal format is `<platform>@<id>`. That can easily be stored in the database.

To unserialize, just use

```php
$serialized = "youtube@123";
$video = Video::createFromString($serialized);

assert("youtube" === $video->getPlatform());
assert("123" === $video->getId()());
```

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
