<?php
/**
 * Created by PhpStorm.
 * User: LetoAtreides
 * Date: 23.05.2016
 * Time: 18:04
 */

namespace HsBremen\WebApi\Tests\Module;


use HsBremen\WebApi\Module\ModuleRoutesProvider;
use Silex\Application;
use Silex\ControllerCollection;

class ModuleRoutesProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function registerIndexRoute()
    {
        $provider = new ModuleRoutesProvider();

        $controllerFactory = $this->prophesize(ControllerCollection::class);
        $controllerFactory->get('/', 'service.module:getList')->shouldBeCalled();
        $controllerFactory->get('/{moduleId}', 'service.module:getDetails')->shouldBeCalled();
        $controllerFactory->post('/', 'service.module:createModule')->shouldBeCalled();
        $controllerFactory->put('/{moduleId}', 'service.module:changeModule')->shouldBeCalled();

        $app = new Application();
        $app['controllers_factory'] = $controllerFactory->reveal();
        $provider->connect($app);
    }

}
