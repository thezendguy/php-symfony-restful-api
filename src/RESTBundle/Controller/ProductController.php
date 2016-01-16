<?php

namespace RESTBundle\Controller;

use RESTBundle\Controller\AbstractRESTController;
use RESTBundle\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/api/v1/")
 */
class ProductController extends Controller implements AbstractRESTController
{
    /**
     * @Route("products/", name="get_all_product")
     * @Method({"GET"})
     */
    public function getAllAction(Request $request)
    {
        $products = $this
            ->getDoctrine()
            ->getRepository('RESTBundle:Product')
            ->findAll();
        
        // Encapsulate the array of Products into another array. When encoded
        // to JSON, this will ensure that the returned data is a JSON object,
        // rather than an array. This will future-proof the response, allowing
        // additional data items to be added at the top-level.
        $products = array('products' => $products);
        
        // Convert the array of products to JSON and return.
        $jsonProducts = $this->get('serializer')->serialize($products, 'json');
        $response = new Response($jsonProducts);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;       
    }
    
    /**
     * @Route("products/{id}", name="get_product")
     * @Method({"GET"})
     */
    public function getAction(Request $request, $id)
    {
        $product = $this
            ->getDoctrine()
            ->getRepository('RESTBundle:Product')
            ->find($id);
            
        if(!$product) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        
        // Convert the Product object to a JSON object.
        $product = $this->get('serializer')->serialize($product, 'json');
        $response = new Response($product);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }
    
    /**
     * Post data must be in JSON format.
     *
     * @Route("products/", name="post_product")
     * @Method({"POST"})
     */
    public function postAction(Request $request)
    {   
        // Check to ensure the client has sent the data in JSON format.
        $content = json_decode($content);
        if(json_last_error() != JSON_ERROR_NONE) {
            // 422 The data cannot be processed.
            $response = new Response();
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            return $response;
        }
        
        if(empty($content->name) || empty($content->price) || empty($content->description)) {
            // 400 Bad request
            $response = new Response();
            $response->setStatusCode(Response::BAD_REQUEST);
            return $response;
        }
        
        $product = new Product();
        $product->setName($content->name);
        $product->setPrice($content->price);
        $product->setDescription($content->description);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        
        // To prevent an API consumer from having to hit the API again for an 
        // updated representation, have the API return the created representation 
        // as part of the response.
        $jsonProduct = $this->get('serializer')->serialize($product, 'json');
        $response = new Response($jsonProduct);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }
    
    /**
     * PUT data must be in JSON format.
     * 
     * @Route("products/{id}", name="put_product")
     * @Method({"PUT"})
     */
    public function putAction(Request $request, $id)
    {   
        // Check to ensure the client has sent the data in JSON format.
        $content = json_decode($content);
        if(json_last_error() != JSON_ERROR_NONE) {
            // 422 the data is unprocessable.
            $response = new Response();
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            return $response;
        }
        
        if(empty($content->name) && empty($content->price) && empty($content->description)) {
            // 400 Bad request
            $response = new Response();
            $response->setStatusCode(Response::BAD_REQUEST);
            return $response;
        }
        
        $product = $this
            ->getDoctrine()
            ->getRepository('RESTBundle:Product')
            ->findOneById($id);
        
        if(!$product) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        
        if(isset($content->name)) {
            $product->setName($content->name);
        }
        if(isset($content->price)) {
            $product->setPrice($content->price);
        }
        if(isset($content->description)) {
            $product->setDescription($content->description);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        
        // To prevent an API consumer from having to hit the API again for an 
        // updated representation, have the API return the updated representation 
        // as part of the response.
        $jsonProduct = $this->get('serializer')->serialize($product, 'json');
        $response = new Response($jsonProduct);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }
    
    /**
     * @Route("products/{id}", name="delete_product")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
        $product = $this
            ->getDoctrine()
            ->getRepository('RESTBundle:Product')
            ->findOneById($id);
        
        if(!$product) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $response;
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        
        // Send a 204 (No Content) to indicate the action has been enacted but 
        // the response does not include an entity
        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        return $response;
    }
}
