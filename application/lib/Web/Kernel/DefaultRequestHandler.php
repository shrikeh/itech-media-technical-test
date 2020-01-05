<?php

declare(strict_types=1);

namespace Shrikeh\TestSymfonyApp\Web\Kernel;

use Shrikeh\TestSymfonyApp\Web\Kernel\RequestHandler\ResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ControllerDoesNotReturnResponseException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class DefaultRequestHandler implements RequestHandlerInterface
{
    private RequestStack $requestStack;
    private EventDispatcherInterface $dispatcher;
    private ResolverInterface $resolver;

    /**
     * DefaultRequestHandler constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param RequestStack $requestStack
     * @param ResolverInterface $resolver
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        RequestStack $requestStack,
        ResolverInterface $resolver
    ) {
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(
        HttpKernelInterface $kernel,
        Request $request,
        int $type = HttpKernelInterface::MASTER_REQUEST,
        bool $catch = true
    ): Response {
        $request->headers->set('X-Php-Ob-Level', (string) ob_get_level());

        $this->requestStack->push($request);

        try {
            return $this->handleRaw($kernel, $request, $type);
        } catch (\Exception $e) {
            if ($e instanceof RequestExceptionInterface) {
                $e = new BadRequestHttpException($e->getMessage(), $e);
            }
            if (false === $catch) {
                $this->finishRequest($kernel, $request, $type);

                throw $e;
            }

            return $this->handleThrowable($kernel, $e, $request, $type);
        }
    }

    /**
     * @param HttpKernelInterface $kernel
     * @param Request $request
     * @param int $type
     * @return Response
     */
    private function handleRaw(
        HttpKernelInterface $kernel,
        Request $request,
        int $type = HttpKernelInterface::MASTER_REQUEST
    ): Response {

        // request
        $event = new RequestEvent($kernel, $request, $type);

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);

        if ($event->hasResponse()) {
            return $this->filterResponse($kernel, $event->getResponse(), $request);
        }

        // load controller
        if (false === $controller = $this->resolver->resolveController($request)) {
            throw new NotFoundHttpException(sprintf(
                'Unable to find the controller for path "%s". The route is wrongly configured.',
                $request->getPathInfo()
            ));
        }

        $event = new ControllerEvent($kernel, $controller, $request, $type);
        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER);
        $controller = $event->getController();

        // controller arguments
        $arguments = $this->resolver->getControllerArguments($request, $controller);

        $event = new ControllerArgumentsEvent($kernel, $controller, $arguments, $request, $type);
        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER_ARGUMENTS);
        $controller = $event->getController();
        $arguments = $event->getArguments();

        // call controller
        $response = $controller(...$arguments);

        // view
        if (!$response instanceof Response) {
            $event = new ViewEvent($kernel, $request, $type, $response);
            $this->dispatcher->dispatch($event, KernelEvents::VIEW);

            if ($event->hasResponse()) {
                $response = $event->getResponse();
            } else {
                $msg = sprintf(
                    'The controller must return a "Symfony\Component\HttpFoundation\Response" object but it returned %s.',
                    $this->varToString($response)
                );

                // the user may have forgotten to return something
                if (null === $response) {
                    $msg .= ' Did you forget to add a return statement somewhere in your controller?';
                }

                throw new ControllerDoesNotReturnResponseException($msg, $controller, __FILE__, __LINE__ - 17);
            }
        }

        return $this->filterResponse($kernel, $response, $request);
    }

    /**
     * Handles a throwable by trying to convert it to a Response.
     *
     * @param HttpKernelInterface $kernel
     * @param Throwable $e
     * @param Request $request
     * @param int $type
     * @return Response
     * @throws Throwable
     */
    private function handleThrowable(
        HttpKernelInterface $kernel,
        Throwable $e,
        Request $request,
        int $type
    ): Response {
        $event = new ExceptionEvent($kernel, $request, $type, $e);
        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);

        // a listener might have replaced the exception
        $e = $event->getThrowable();

        if (!$event->hasResponse()) {
            $this->finishRequest($kernel, $request, $type);

            throw $e;
        }

        return $this->resolveEventResponseStatusCode($kernel, $request, $event, $type);
    }

    /**
     * @param HttpKernelInterface $kernel
     * @param Request $request
     * @param ExceptionEvent $event
     * @param int $type
     * @return Response
     */
    private function resolveEventResponseStatusCode(
        HttpKernelInterface $kernel,
        Request $request,
        ExceptionEvent $event,
        int $type
    ): Response {
        if ($response = $event->getResponse()) {
            // the developer asked for a specific status code
            if (!$event->isAllowingCustomResponseCode() && !$response->isClientError() && !$response->isServerError() && !$response->isRedirect()) {
                // ensure that we actually have an error response
                $e = $event->getThrowable();
                if ($e instanceof HttpExceptionInterface) {
                    // keep the HTTP status code and headers
                    $response->setStatusCode($e->getStatusCode());
                    $response->headers->add($e->getHeaders());
                } else {
                    $response->setStatusCode(500);
                }
            }

            try {
                return $this->filterResponse($kernel, $response, $request, $type);
            } catch (\Exception $e) {
                return $response;
            }
        }
    }

    /**
     * @param HttpKernelInterface $kernel
     * @param Request $request
     * @param Response $response
     */
    public function terminate(HttpKernelInterface $kernel, Request $request, Response $response): void
    {
        $this->dispatcher->dispatch(new TerminateEvent($kernel, $request, $response), KernelEvents::TERMINATE);
    }

    /**
     * Filters a response object.
     *
     * @param HttpKernelInterface $kernel
     * @param Response $response
     * @param Request $request
     * @return Response
     */
    private function filterResponse(HttpKernelInterface $kernel, Response $response, Request $request): Response
    {
        $event = new ResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $response);

        $this->dispatcher->dispatch($event, KernelEvents::RESPONSE);
        $this->finishRequest($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        return $event->getResponse();
    }

    /**
     * Publishes the finish request event, then pop the request from the stack.
     *
     * Note that the order of the operations is important here, otherwise
     * operations such as {@link RequestStack::getParentRequest()} can lead to
     * weird results.
     * @param HttpKernelInterface $kernel
     * @param Request $request
     * @param int $type
     */
    private function finishRequest(HttpKernelInterface $kernel, Request $request, int $type): void
    {
        $this->dispatcher->dispatch(new FinishRequestEvent($kernel, $request, $type), KernelEvents::FINISH_REQUEST);
        $this->requestStack->pop();
    }

    /**
     * Returns a human-readable string for the specified variable.
     * @param mixed $var
     * @return string
     */
    private function varToString($var): string
    {
        if (\is_object($var)) {
            return sprintf('an object of type %s', \get_class($var));
        }

        if (\is_array($var)) {
            $a = [];
            foreach ($var as $k => $v) {
                $a[] = sprintf('%s => ...', $k);
            }

            return sprintf('an array ([%s])', mb_substr(implode(', ', $a), 0, 255));
        }

        if (\is_resource($var)) {
            return sprintf('a resource (%s)', get_resource_type($var));
        }

        if (null === $var) {
            return 'null';
        }

        if (false === $var) {
            return 'a boolean value (false)';
        }

        if (true === $var) {
            return 'a boolean value (true)';
        }

        if (\is_string($var)) {
            return sprintf('a string ("%s%s")', mb_substr($var, 0, 255), mb_strlen($var) > 255 ? '...' : '');
        }

        if (is_numeric($var)) {
            return sprintf('a number (%s)', (string) $var);
        }

        return (string) $var;
    }
}
