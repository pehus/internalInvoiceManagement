<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiKeyAuthenticator
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!str_starts_with($request->getPathInfo(), '/api/'))
        {
            return;
        }

        $apiKey = $request->headers->get('X-API-KEY');
        $secretKey = $_ENV['API_SECRET_KEY'] ?? null;
        if ($apiKey !== $secretKey)
        {
            $event->setResponse(new JsonResponse(['message' => 'Unauthorized'], 401));
        }
    }
}