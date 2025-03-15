<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class ExceptionListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $errorMessage = 'An unexpected error occurred: ' . $event->getThrowable()->getMessage();

        if ($exception instanceof HttpExceptionInterface)
        {
            $statusCode = $exception->getStatusCode();
            $errorMessage = $exception->getMessage();
        }
        else
        {
            $this->logger->error('Unhandled exception: ' . $exception->getMessage());
        }

        $response = new JsonResponse([
            'error' => true,
            'message' => $errorMessage
        ], $statusCode);

        $event->setResponse($response);
    }
}
