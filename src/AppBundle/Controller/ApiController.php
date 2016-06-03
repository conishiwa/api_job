<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class ApiController extends Controller
{

    /**
     * @Route("/", name="api_index")
     */
    public function indexAction(Request $request)
    {
        return new JsonResponse("API INDEX");
    }


    /**
     * @Route("/jobs", name="api_job_listing")
     */
    public function jobListingAction(Request $request)
    {
        $qb = $this->getDoctrine()
            ->getRepository('AppBundle:Job')
            ->findAllQueryBuilder();

        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_job_listing');

        $serializedJobs = $this->container->get('jms_serializer')->serialize($paginatedCollection, 'json');

        // replace this example code with whatever you need
        return new Response($serializedJobs,200,array(
            'Content-Type' => 'application/json'
        ));
    }







}
