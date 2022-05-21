<?php

namespace CI;

interface ServiceContainerInterface
{
    public function has(string $id): bool;
    public function get(string $id, bool $shared = true): object;

    /**
     * @param \Closure|string $service
     */
    public function set(string $id, $service): void;
}
