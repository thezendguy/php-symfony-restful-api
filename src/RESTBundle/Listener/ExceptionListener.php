<?php

namespace RESTBundle\Listener;

use RESTBundle\Controller\RESTController;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function __construct()
    {
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        // Listen for 406 http exceptions.
        if ($exception instanceof NotAcceptableHttpException) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            $event->setResponse($response);
            return;
        }

        // This will capture invalid URLs.
        if ($exception instanceof \Exception) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $event->setResponse($response);
            return;
        }
    }
}
