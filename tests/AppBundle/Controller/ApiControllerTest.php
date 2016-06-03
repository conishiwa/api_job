<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{

    private $client;

    /**
     * @before
     */
    public function setup()
    {
        $this->client = static::createClient();
    }



    public function testJobListing()
    {

        $crawler = $this->client->request('GET', '/api/jobs');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);


        $content = json_decode($response->getContent(), true);
        $this->assertEquals(
            count($content['items']), $content['count']
        );

    }

    protected function assertJsonResponse($response, $statusCode = 200) {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

}
