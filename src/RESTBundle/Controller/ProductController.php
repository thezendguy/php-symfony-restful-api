<?php

namespace RESTBundle\Controller;

use RESTBundle\Controller\RESTController;
use RESTBundle\Entity\Product;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/api/v1/")
 */
class ProductController extends Controller implements RESTController
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
     * @Route("products/", name="post_product")
     * @Method({"POST"})
     */
    public function postAction(Request $request)                                        // HERE What format is data received in?
    {
        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $product->setDescription($request->request->get('description'));
        
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
     * @Route("products/{id}", name="put_product")
     * @Method({"PUT"})
     */
    public function putAction(Request $request, $id)                                    // HERE What format is the data received in?
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
        
        $product->setName($request->request->get('name'));
        $product->setPrice($request->request->get('price'));
        $product->setDescription($request->request->get('description'));

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
