<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiLogger
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function log(Request $request): JsonResponse
    {
        $this->logger->warning('Неавторизованный пользователь пытался получить доступ к API: ', [
            'route' => $request->attributes->get('_route'),
            'url' => $request->getUri(),
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);

        return new JsonResponse([
            'message' => 'Нет доступа'
        ], 401);
    }
}
