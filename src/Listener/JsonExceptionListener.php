<?php

namespace App\Listener;

use App\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class JsonExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof JsonHttpException) {
            $errorData = [
                'error' => [
                    'code' => $exception->getStatusCode(),
                    'message' => $exception->getMessage(),
                ],
            ];
            if (($data = $exception->getData())) {
                $errorData['error']['fields'] = $data;
            }
            $response = new JsonResponse($errorData);
            $event->setResponse($response);
        }
    }
}
