<?php

namespace CI;

class ServiceContainer implements ServiceContainerInterface
{
    /**
     * @var array<string, array{'builder': \Closure|string, 'shared': bool, 'instance': ?object}>
     */
    protected array $container = [];

    /**
     * @param \Closure|string $service
     *
     * @throws ServiceContainerException
     */
    public function set(string $id, $service, bool $shared = false): void
    {
        if ($this->has($id)) {
            throw ServiceContainerException::exists();
        }

        $this->checkBuilderType($service);

        $this->container[$id] = [
            'builder'  => $service,
            'shared'   => $shared,
            'instance' => null,
        ];
    }

    /**
     * @param \Closure|string $service
     *
     * @throws ServiceContainerException
     */
    public function singleton(string $id, $service): void
    {
        $this->set($id, $service, true);
    }

    /**
     * @throws ServiceContainerException
     */
    public function get(string $id, bool $shared = true): object
    {
        if (! $this->has($id)) {
            throw ServiceContainerException::notFound();
        }

        if ($this->container[$id]['shared'] && $shared) {
            if ($this->container[$id]['instance'] === null) {
                $this->container[$id]['instance'] = $this->getServiceInstance($this->container[$id]['builder']);
            }

            return $this->container[$id]['instance'];
        }

        return $this->getServiceInstance($this->container[$id]['builder']);
    }

    /**
     * @throws ServiceContainerException
     */
    public function instance(string $id): object
    {
        return $this->get($id, false);
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }

    /**
     * @param Closure|object|string $builder
     *
     * @return object
     */
    protected function getServiceInstance($builder): object
    {
        if ($builder instanceof \Closure) {
            return $builder($this);
        }

        return new $builder();
    }

    /**
     * @param mixed $builder
     * @return void
     * @throws ServiceContainerException
     */
    protected function checkBuilderType($builder)
    {
        if (! ($builder instanceof \Closure) && (! is_string($builder) || ! class_exists($builder))) {
            throw ServiceContainerException::builderType(gettype($builder));
        }
    }
}
