<?php

namespace CI;

class ServiceContainerException extends \Exception
{
    public static function exists(): self
    {
        return new self('Service already registered');
    }

    public static function notFound(): self
    {
        return new self('Service not found in container');
    }

    public static function builderType(string $type): self
    {
        return new self('Unable to build a service instance. Unavailable type: ' . $type . '.');
    }
}
