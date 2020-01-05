<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Web\Kernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

interface RequestHandlerInterface
{
    /**
     * @param HttpKernelInterface $kernel
     * @param Request $request
     * @param int $type
     * @param bool $catch
     * @return mixed
     */
    public function handle(
        HttpKernelInterface $kernel,
        Request $request,
        int $type = HttpKernelInterface::MASTER_REQUEST,
        bool $catch = true
    ): Response;

    /**
     * @param HttpKernelInterface $kernel
     * @param Request $request
     * @param Response $response
     */
    public function terminate(HttpKernelInterface $kernel, Request $request, Response $response): void;
}
