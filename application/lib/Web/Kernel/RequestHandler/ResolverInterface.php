<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Web\Kernel\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface ResolverInterface
{
    /**
     * @param Request $request
     * @return callable|null
     */
    public function resolveController(Request $request): ?callable ;

    /**
     * @param Request $request
     * @param callable $controller
     * @return array
     */
    public function getControllerArguments(Request $request, callable $controller): array;
}
