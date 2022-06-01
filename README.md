## ServiceContainer

This is an attempt at a simple implementation of the Container interface.

```php
$container = new \CI\ServiceContainer();

// registering a service in a container
$container->set(ServiceClassInterface::class, ServiceClass::class);

// registering a service in a container when the service id is the same as the service class name.
$container->set(Service::class);
// equal
$container->set(Service::class, Service::class);

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

// registering a service in a container from an object.
// in this case, the service will be registered as a singleton.
$instance = new Service();
$container->set('Service', $instance);

// getting an instance of the service class.
$service = $container->get(ServiceClassInterface::class);

// even if the service is specified as a singleton, it is possible to obtain a new instance of the class
// without changing the state of the service.
$service = $container->instance(ServiceClassInterface::class);
```
