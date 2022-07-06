<?php

namespace CI;

class ServiceContainer implements ServiceContainerInterface
{
    /**
     * @var array<string, array{'builder': \Closure|string, 'shared': bool, 'instance': ?object}>
     */
    protected array $container = [];

    /**
     * @param \Closure|object|string|null $service
     *
     * @throws ServiceContainerException
     */
    public function set(string $id, $service = null, bool $shared = false): void
    {
        if ($this->has($id)) {
            throw ServiceContainerException::exists();
        }

        if (is_null($service)) {
            $service = $id;
        }

        $this->checkBuilderType($service);

        $isInstance = is_object($service) && ! ($service instanceof \Closure);

        $this->container[$id] = [
            'builder'  => $service,
            'shared'   => $isInstance ? true : $shared,
            'instance' => $isInstance ? $service : null,
        ];
    }

    /**
     * @param \Closure|object|string|null $service
     *
     * @throws ServiceContainerException
     */
    public function singleton(string $id, $service = null): void
    {
        $this->set($id, $service, true);
    }

    /**
     * @throws ServiceContainerException
     */
    public function get(string $id, bool $shared = true): object
    {
        return $this->getService($id);
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }

    /**
     * @param Closure|object|string $builder
     *
     */
    protected function getServiceInstance($builder): object
    {
        if ($builder instanceof \Closure) {
            return $builder($this);
        }

        return new $builder();
    }

    /**
     * @throws ServiceContainerException
     */
    protected function getService(string $id, bool $shared = true): object
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
     * @param mixed $builder
     *
     * @return void
     * @throws ServiceContainerException
     */
    protected function checkBuilderType($builder)
    {
        if (! is_object($builder) && (! is_string($builder) || ! class_exists($builder))) {
            throw ServiceContainerException::builderType(gettype($builder));
        }
    }
}
