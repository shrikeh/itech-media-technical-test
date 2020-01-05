<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\ServiceProvider;

use Pimple\Container;
use Pimple\Psr11\ServiceLocator;
use Pimple\ServiceProviderInterface;
use Psr\Container\ContainerInterface;
use Shrikeh\TestSymfonyApp\Booter\BooterInterface;
use Shrikeh\TestSymfonyApp\Kernel\ConfigurationLoader\ConfigurationLoaderInterface;
use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Shrikeh\TestSymfonyApp\Web\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

final class WebProvider implements ServiceProviderInterface
{
    /**
     * @param Container|null $container
     * @return ContainerInterface
     */
    public static function serviceLocator(Container $container = null): ContainerInterface
    {
        return new ServiceLocator(static::create($container), [
            HttpKernelInterface::class,
            Request::class
        ]);
    }

    /**
     * @param Container|null $container
     * @return Container
     */
    public static function create(Container $container = null): Container
    {
        $container = $container ?? KernelProvider::create();
        $container->register(new self());

        return $container;
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function register(Container $p): void
    {
        $p[HttpKernelInterface::class] = static function (Container $c) {
            return $c[KernelInterface::class];
        };

        $p[KernelInterface::class] = static function (Container $c) {
            return new Kernel(
                $c[EnvironmentInterface::class],
                $c[BooterInterface::class],
                $c[ConfigurationLoaderInterface::class]
            );
        };

        $p[Request::class] = static function () {
            return Request::createFromGlobals();
        };
    }
}
