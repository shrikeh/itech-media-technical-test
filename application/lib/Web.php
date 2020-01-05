<?php
declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp;

use App\ServiceProvider\BundleProvider;
use Shrikeh\TestSymfonyApp\ServiceProvider\KernelProvider;
use Shrikeh\TestSymfonyApp\ServiceProvider\WebProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

final class Web
{
    /**
     * @throws \Exception
     */
    public static function init()
    {
        if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
            Request::setTrustedProxies(
                explode(',', $trustedProxies),
                Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST
            );
        }

        if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
            Request::setTrustedHosts([$trustedHosts]);
        }

        $container = KernelProvider::create();
        $container->register(new BundleProvider());

        $serviceLocator = WebProvider::serviceLocator($container);

        /** @var HttpKernelInterface $kernel */
        $kernel = $serviceLocator->get(HttpKernelInterface::class);
        /** @var Request $request */
        $request = $serviceLocator->get(Request::class);

        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }
}
