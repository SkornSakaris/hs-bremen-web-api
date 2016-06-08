<?php

namespace HsBremen\WebApi\Tests\User;

use HsBremen\WebApi\Application;
use Silex\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserInterfaceTest extends WebTestCase
{

    /**
     * @inheritdoc
     */
    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;
        $app['session.test'] = true;
        unset($app['exception_handler']);

        return $app;
    }

    /**
     * @test#
     */
    public function testInitialPage()
    {
        $client = $this->createClient();

        $options = [
            'HTTP_Accept'   => 'application/json',
        ];

        $crawler = $client->request('GET', '/', [], [], $options);

        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertCount(1, $crawler->filter('h3:contains("Willkommen!")'));
//        $this->assertCount(1, $crawler->filter('form'));
    }

    /**
     * @test
     */
    public function testUserPageUnauthorized()
    {
        $client = $this->createClient();

        $options = [
            'HTTP_Accept'   => 'application/json',
        ];

        $crawler = $client->request('GET', '/user', [], [], $options);

        $response = $client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/login'));
    }

    /**
     * @test
     */
    public function shouldReturnAllUsersAsJSON()
    {
        $client = $this->createClient();

        $client = $this->loginAsJannik($client);

        $options = [
            'HTTP_Accept'   => 'application/json',
        ];

        $client->request('GET', '/user', [], [], $options);

        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertJson($response->getContent());
        $this->assertArrayHasKey('users', json_decode($response->getContent(), true));
    }

    /**
     * @param Client $client
     * @return Client $client
     */
    public function loginAsJannik($client)
    {
        $client->followRedirects();

        $options = [
            'HTTP_Accept'   => 'text/html',
        ];

        $client->request('GET', '/login');
        $client->request('POST', '/login_check', ['_username' => 'Jannik', '_password' => 'foo'], [], $options);

        return $client;
    }
}