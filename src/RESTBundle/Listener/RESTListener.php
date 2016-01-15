<?php

namespace RESTBundle\Listener;

use RESTBundle\Controller\RESTController;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpFoundation\Response;

class RESTListener
{
    public function __construct()
    {
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
;
        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof RESTController) {

            // Check to ensure the client accepts a JSON response. Remember, if no 
            // Accept header field is present, then it is assumed that the client 
            // accepts all media types.
            $request = $event->getRequest();
            $acceptsJsonContent = in_array('application/json', $request->getAcceptableContentTypes());
            if(!$acceptsJsonContent) {

                throw new NotAcceptableHttpException();
            }
        }
    }
}
