<?php declare(strict_types=1);

namespace Becklyn\VideoPlatforms\Video;

use Becklyn\VideoPlatforms\Exception\InvalidVideoDetailsException;
use Becklyn\VideoPlatforms\Exception\VideoUnserializeException;

final class Video
{
    private string $platform;
    private string $id;


    /**
     */
    public function __construct (string $platform, string $id)
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
    }


    /**
     */
    public function getPlatform () : string
    {
        return $this->platform;
    }


    /**
     */
    public function setPlatform (string $platform) : void
    {
        $this->platform = $platform;
    }


    /**
     */
    public function getId () : string
    {
        return $this->id;
    }


    /**
     */
    public function setId (string $id) : void
    {
        $this->id = $id;
    }


    /**
     */
    public function __toString () : string
    {
        return $this->serialize();
    }


    /**
     */
    public function serialize () : string
    {
        return "{$this->platform}@{$this->id}";
    }


    /**
     */
    public static function createFromString (?string $value) : ?self
    {
        if (null === $value || "" === $value)
        {
            return null;
        }

        $video = self::parse($value);

        if (null === $video)
        {
            throw new VideoUnserializeException(\sprintf("Can't unserialize video id: %s", $value));
        }

        return $video;
    }


    /**
     * Parses the value
     */
    public static function parse (?string $value) : ?self
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
