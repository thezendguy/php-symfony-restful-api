<?php

namespace RESTBundle\Controller;

/**
 * This interface should be implemented by all RESTful controllers. It will then
 * ensure that the implementing Controller can be protected from 'Accept' headers
 * that do not explicitly support 'application/json'.
 */
interface AbstractRESTController
{
}
