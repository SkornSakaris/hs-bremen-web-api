<?php
/**
 * Created by PhpStorm.
 * User: LetoAtreides
 * Date: 02.06.2016
 * Time: 16:08
 */

namespace HsBremen\WebApi\Tests\User;

use Silex\WebTestCase;

class UserInterfaceTest extends WebTestCase
{

    /**
     * @inheritdoc
     */
    public function createApplication()
    {
//        $app = require __DIR__.'/../../src/Application.php';
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
        $crawler = $client->request('GET', '/');

//        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertCount(1, $crawler->filter('h1:contains("Contact us")'));
//        $this->assertCount(1, $crawler->filter('form'));
    }
}