<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Web;

use Shrikeh\TestSymfonyApp\Booter\BooterInterface;
use Shrikeh\TestSymfonyApp\Kernel\ConfigurationLoader\ConfigurationLoaderInterface;
use Shrikeh\TestSymfonyApp\Kernel\Environment\EnvironmentInterface;
use Shrikeh\TestSymfonyApp\Kernel\Traits\BooterTrait;
use Shrikeh\TestSymfonyApp\Kernel\Traits\ConfigurationLoaderTrait;
use Shrikeh\TestSymfonyApp\Kernel\Traits\EnvironmentTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

final class Kernel implements KernelInterface, TerminableInterface
{
    use BooterTrait;
    use ConfigurationLoaderTrait;
    use EnvironmentTrait;

    public const SERVER_CACHE_DIR = 'SYMFONY_CACHE_DIR';
    public const SERVER_LOG_DIR = 'SYMFONY_LOG_DIR';

    /**
     * Kernel constructor.
     * @param EnvironmentInterface $environment
     * @param BooterInterface $booter
     * @param ConfigurationLoaderInterface $configurationLoader
     */
    public function __construct(
        EnvironmentInterface $environment,
        BooterInterface $booter,
        ConfigurationLoaderInterface $configurationLoader
    ) {
        $this->environment = $environment;
        $this->booter = $booter;
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * As this is a console command, this is not a valid use case, so we throw an exception.
     * {@inheritDoc}
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $this->boot();

        return $this->getHttpKernel()->handle($request, $type, $catch);
    }

    /**
     * {@inheritDoc}
     */
    public function getBundle($name): Bundle
    {
        // TODO: Implement getBundle() method.
    }

    /**
     * {@inheritDoc}
     */
    public function locateResource($name)
    {
        // TODO: Implement locateResource() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * @return string
     */
    public function getProjectDir(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * {@inheritDoc}
     */
    public function getRootDir(): string
    {
        return $this->getProjectDir();
    }

    /**
     * {@inheritDoc}
     */
    public function getStartTime()
    {
        // TODO: Implement getStartTime() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheDir(): string
    {
        return $this->getFromServerBag(
            self::SERVER_CACHE_DIR,
            $this->getProjectDir() . '/var/cache'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getLogDir()
    {
        return $this->getFromServerBag(
            self::SERVER_LOG_DIR,
            $this->getProjectDir() . '/var/logs'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response): void
    {
        if (!$this->isBooted()) {
            return;
        }

        if ($this->getHttpKernel() instanceof TerminableInterface) {
            $this->getHttpKernel()->terminate($request, $response);
        }
    }

    /**
     * @return HttpKernelInterface
     */
    private function getHttpKernel(): HttpKernelInterface
    {
        return $this->booter->getContainer()->get('http_kernel');
    }
}
