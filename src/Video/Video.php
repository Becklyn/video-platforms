<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Video;

use Becklyn\VideoPlatforms\Exception\InvalidVideoDetailsException;
use Becklyn\VideoPlatforms\Exception\VideoUnserializeException;

final class Video
{
    private string $platform;
    private string $id;
    private string $url;


    /**
     */
    public function __construct (string $platform, string $id, ?string $url = null)
    {
        if ("" === $platform || !\preg_match('~^[\\w_-]+$~', $platform))
        {
            throw new InvalidVideoDetailsException(\sprintf("Invalid platform: %s", $platform));
        }

        if ("" === $id || false !== \strpos("$id", "@"))
        {
            throw new InvalidVideoDetailsException(\sprintf("Invalid id: %s", $id));
        }

        $this->platform = $platform;
        $this->id = $id;
        $this->url = $url ?? "{$platform}@{$id}";
    }


    /**
     */
    public function getPlatform () : string
    {
        return $this->platform;
    }


    /**
     */
    public function getId () : string
    {
        return $this->id;
    }


    /**
     * @return string|null
     */
    public function getUrl () : ?string
    {
        return $this->url;
    }



    /**
     */
    public function toArray () : array
    {
        return [
            "platform" => $this->platform,
            "id" => $this->id,
            "url" => $this->url,
        ];
    }


    /**
     */
    public static function createFromArray (?array $value) : ?self
    {
        if (null === $value)
        {
            return null;
        }

        if (!isset($value["platform"], $value["id"]) || !\is_string($value["platform"]) || !\is_string($value["id"]))
        {
            throw new VideoUnserializeException("Can't unserialize video array");
        }

        $url = $value["url"] ?? null;

        if (null !== $url && !\is_string($url))
        {
            throw new VideoUnserializeException("Can't unserialize video array");
        }

        return new self($value["platform"], $value["id"], $url);
    }


    /**
     * @return string
     */
    public function serialize () : string
    {
        return "{$this->platform}@{$this->id}";
    }


    /**
     * Parses the value
     */
    public static function unserialize (?string $value) : ?self
    {
        if (null === $value || "" === $value)
        {
            return null;
        }

        return \preg_match('~^(?<platform>[\\w_-]+)@(?<id>[^@]+)$~', $value, $matches)
            ? new self($matches["platform"], $matches["id"])
            : null;
    }
}
