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

    /**
     * @Todo
     * Currently this method will capture \Exceptions and treat them as 404
     * errors, which for the most part is correct. However, internal server errors
     * will also be treated this way. A better solution is necessary to determine
     * if the exception is correctly a 404.
     */
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
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
            return;
        }
    }
}
