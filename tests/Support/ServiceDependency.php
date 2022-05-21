<?php

namespace Support;

class ServiceDependency
{
    protected Service $service;

    /**
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }
}
