<?php

namespace AppBundle\Controller\Api;

use AppBundle\Controller\BaseController;
use AppBundle\Api\ApiProblem;
use AppBundle\Api\ApiProblemException;
use AppBundle\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


//  https://jwt.io/
//  https://github.com/lexik/LexikJWTAuthenticationBundle
//  https://knpuniversity.com/screencast/oauth
//  http://stackoverflow.com/questions/24709944/jwt-token-in-postman-header
//  https://auth0.com/docs/refresh-token
//  http://knpuniversity.com/screencast/symfony-rest/test-database

/**
 * @Security("is_granted('ROLE_USER')")
 */
class ApiController extends BaseController
{

    /**
     * @Route("/", name="api_index")
     */
    public function indexAction(Request $request)
    {

        $apiProblem = new ApiProblem(400,ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

        throw new ApiProblemException($apiProblem);

        //return new JsonResponse("API INDEX");
    }


    /**
     * @Route("/jobs", name="api_job_listing")
     */
    public function jobListingAction(Request $request)
    {
        $filter = $request->query->get('filter');

        $qb = $this->getDoctrine()
            ->getRepository('AppBundle:Job')
            ->findAllQueryBuilder($filter);

        $paginatedCollection = $this->get('pagination_factory')
            ->createCollection($qb, $request, 'api_job_listing');

        return $this->createApiResponse($paginatedCollection, 200);
    }







}
