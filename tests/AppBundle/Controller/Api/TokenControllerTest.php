<?php

namespace Tests\AppBundle\Controller\Api;

use AppBundle\Test\ApiTestCase;

class TokenControllerTest extends ApiTestCase
{
    public function testPOSTCreateToken()
    {
        $this->createUser('conishiwa', 'foo');

        $response = $this->client->post('/api/tokens', [
            'auth' => ['conishiwa', 'foo']
        ]);

        $pageContent = strval($response->getBody());


        file_put_contents('bonsoir.html',$pageContent);



        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyExists(
            $response,
            'token'
        );
    }

    public function testPOSTTokenInvalidCredentials()
    {
        $this->createUser('conishiwa', 'I<3Chips');

        $response = $this->client->post('/api/tokens', [
            'auth' => ['conishiwa', 'IH8Chips']
        ]);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeader('Content-Type')[0]);
        $this->asserter()->assertResponsePropertyEquals($response, 'type', 'about:blank');
        $this->asserter()->assertResponsePropertyEquals($response, 'title', 'Unauthorized');
        $this->asserter()->assertResponsePropertyEquals($response, 'detail', 'Invalid credentials.');
    }
}
