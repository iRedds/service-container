<?php

use CI\ServiceContainer;
use CI\ServiceContainerException;
use PHPUnit\Framework\TestCase;
use Support\Service;
use Support\ServiceDependency;

class ServiceContainerTest extends TestCase
{
    public function testSetService(): void
    {
        $container = new ServiceContainer();

        $container->set('service', static fn () => new Service());

        $service = $container->get('service');

        $this->assertInstanceOf(Service::class, $service);

        $service1 = $container->get('service');

        $this->assertInstanceOf(Service::class, $service1);

        $this->assertEquals($service, $service1);
        $this->assertTrue($service !== $service1);
    }

    public function testSetServiceSingleton(): void
    {
        $container = new ServiceContainer();

        $container->singleton('service', static fn () => new Service());

        $service = $container->get('service');

        $this->assertInstanceOf('Support\Service', $service);
        $this->assertSame(1, $service->value);

        $service->value = 2;

        $service1 = $container->get('service');

        $this->assertInstanceOf('Support\Service', $service1);

        $this->assertEquals($service, $service1);
        $this->assertSame($service, $service1);
        $this->assertSame(2, $service->value);
    }

    public function testSingletonNewInstance(): void
    {
        $container = new ServiceContainer();

        $container->singleton('service', static fn () => new Service());

        $service = $container->get('service');
        $service1 = $container->get('service');
        $service3 = $container->instance('service');

        $this->assertSame($service, $service1);
        $this->assertNotSame($service, $service3);
    }

    public function testAddService(): void
    {
        $container = new ServiceContainer();

        $container->set('service', static fn () => new Service());
        $container->set('service2', Service::class);

        $this->assertInstanceOf('Support\Service', $container->get('service'));
        $this->assertInstanceOf('Support\Service', $container->get('service2'));
    }

    public function testHasService(): void
    {
        $container = new ServiceContainer();

        $container->set('service', static fn () => new Service());

        $this->assertTrue($container->has('service'));
        $this->assertFalse($container->has('service1'));
    }

    /**
     * @dataProvider serviceExceptionDataProvider
     */
    public function testException(string $message, \Closure $binder, \Closure $builder): void
    {
        $container = new ServiceContainer();

        $this->expectException(ServiceContainerException::class);
        $this->expectExceptionMessage($message);

        $binder($container);
        $builder($container);
    }

    /**
     * @return array<int, array<int, \Closure|string>>
     */
    public function serviceExceptionDataProvider(): array
    {
        return [
            [
                'Service not found in container',
                fn () => null,
                fn ($container) => $container->get('service'),
            ],
            [
                'Service already registered',
                function ($container) {
                    $container->set('service', static fn () => new Service());
                    $container->set('service', static fn () => new Service());
                },
                fn () => null,
            ],
            [
                'Unable to build a service instance. Unavailable type: array.',
                fn ($container) => $container->set('service', []),
                fn () => null,
            ],
        ];
    }

    public function testServiceConstructorDependency(): void
    {
        $container = new ServiceContainer();

        $container->singleton(Service::class, Service::class);

        $container->set(ServiceDependency::class, function ($container) {
            return new ServiceDependency($container->get(Service::class));
        });

        /**@var $serviceDependency ServiceDependency */
        $serviceDependency = $container->get(ServiceDependency::class);
        /**@var $serviceDependency2 ServiceDependency */
        $serviceDependency2 = $container->get(ServiceDependency::class);

        $this->assertEquals($serviceDependency, $serviceDependency2);
        $this->assertNotSame($serviceDependency, $serviceDependency2);
        $this->assertSame($serviceDependency->getService(), $serviceDependency2->getService());
    }

    public function testBuildNewInstance(): void
    {
        $container = new ServiceContainer();

        $container->singleton(Service::class, Service::class);

        $service     = $container->get(Service::class);
        $service2    = $container->get(Service::class);
        $newInstance = $container->instance(Service::class);

        $this->assertSame($service, $service2);
        $this->assertNotSame($service, $newInstance);
        $this->assertEquals($service, $newInstance);
    }
}
