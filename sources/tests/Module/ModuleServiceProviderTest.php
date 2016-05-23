<?php
/**
 * Created by PhpStorm.
 * User: LetoAtreides
 * Date: 23.05.2016
 * Time: 20:58
 */

namespace HsBremen\WebApi\Tests\Module;


use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Module\ModuleRepository;
use HsBremen\WebApi\Module\ModuleService;
use HsBremen\WebApi\Module\ModuleServiceProvider;
use Silex\Application;

class ModuleServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function moduleServiceIsRegistered()
    {
        $moduleRepo = self::getMockBuilder(ModuleRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $connection = self::getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $provider = new ModuleServiceProvider();
        $app      = new Application();

        $app['repo.module'] = $moduleRepo;
        $app['db']         = $connection;

        $provider->register($app);

        self::assertArrayHasKey('service.module', $app);
        self::assertInstanceOf(ModuleService::class, $app['service.module']);
    }
}
