<?php

namespace RESTBundle\Listener;

use RESTBundle\Controller\RESTController;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Response;

class RESTListener
{
    public function __construct()
    {
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

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
            
                // Build a 406 status code header
                $error = array('error' => 'Not acceptable');
                $response = new Response(json_encode($error));
                $response->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
                
                // Setting the response on the following object will force the response
                // to be returned to the client immediately.
                $event = new GetResponseForExceptionEvent();
                $event->setResponse($response);
                
                // HERE HERE HERE HERE HERE HERE HERE HERE HERE HERE HERE THROW EXCEPTION HERE
            }
        }
    }
}
