<?php
/**
 * Created by PhpStorm.
 * User: LetoAtreides
 * Date: 02.06.2016
 * Time: 16:08
 */

namespace HsBremen\WebApi\Tests\User;

use HsBremen\WebApi\Application;
use Silex\WebTestCase;
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

        $client->request('GET', '/', [], [], $options);

        $response = $client->getResponse();

        var_dump($response);

//        self::assertInstanceOf(JsonResponse::class, $response);

//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertCount(1, $crawler->filter('h1:contains("Contact us")'));
//        $this->assertCount(1, $crawler->filter('form'));
    }
}