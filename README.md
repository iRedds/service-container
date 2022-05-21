## ServiceContainer

This is an attempt at a simple implementation of the Container interface.

```php
$container = new \CI\ServiceContainer();

// registering a service in a container
$container->set(ServiceClassInterface::class, ServiceClass::class);

// getting an instance of the service class.
$service = $container->get(ServiceClassInterface::class);

// registering a service in a container as a singleton.
$container->singleton(ServiceClassInterface::class, ServiceClass::class);

// if the service constructor expects arguments, they can be specified by passing a closure instead of
// the class name.
$container->set('new_service', static function ($container) {
    return new ServiceClass(
        $container->get(ServiceClassInterface::class),
        ['array', 'of', 'values']
    );
});

// even if the service is specified as a singleton, it is possible to obtain a new instance of the class
// without changing the state of the service.
$service = $container->instance(ServiceClassInterface::class);

```
