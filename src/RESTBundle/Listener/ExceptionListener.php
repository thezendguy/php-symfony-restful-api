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

            $error = array('error' => 'Not acceptable');
            $response = new Response(json_encode($error));
            $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
            $event->setResponse($response);
        }
    }
}
