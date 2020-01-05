<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Web\Kernel\RequestHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

final class DefaultControllerResolver implements ResolverInterface
{
    private ControllerResolverInterface $controllerResolver;

    private ArgumentResolverInterface $argumentResolver;

    /**
     * DefaultControllerResolver constructor.
     * @param ControllerResolverInterface $controllerResolver
     * @param ArgumentResolverInterface $argumentResolver
     */
    public function __construct(ControllerResolverInterface $controllerResolver, ArgumentResolverInterface $argumentResolver)
    {
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    /**
     * @param Request $request
     * @return callable|null
     */
    public function resolveController(Request $request): ?callable
    {
        $controller = $this->controllerResolver->getController($request);

        return $controller ?? null;
    }

    /**
     * @param Request $request
     * @param callable $controller
     * @return array
     */
    public function getControllerArguments(Request $request, callable $controller): array
    {
        return $this->argumentResolver->getArguments($request, $controller);
    }
}
