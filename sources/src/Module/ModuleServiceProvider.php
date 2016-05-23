<?php

namespace HsBremen\WebApi\Module;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ModuleServiceProvider implements ServiceProviderInterface
{
    /** {@inheritdoc} */
    public function register(Application $app)
    {
        $app['repo.module'] = $app->share(function (Application $app) {
            return new ModuleRepository($app['db']);
        });

        $app['service.module'] = $app->share(function (Application $app) {
            return new ModuleService($app['repo.module']);
        });

        $app->mount('/module', new ModuleRoutesProvider());
    }

    /** {@inheritdoc} */
    public function boot(Application $app)
    {
        /** @var ModuleRepository $repo */
        $repo = $app['repo.module'];
        $repo->dropTable();
        $repo->createTable();
        $repo->createTestData();
    }
}