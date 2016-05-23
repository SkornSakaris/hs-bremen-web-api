<?php

namespace HsBremen\WebApi\Start;

use Silex\Application;
use Silex\ServiceProviderInterface;

class StartServiceProvider implements ServiceProviderInterface
{
    /** {@inheritdoc} */
    public function register(Application $app)
    {
        $app['service.start'] = $app->share(function () {
            return new StartService();
        });

        $app->mount('/', new StartRoutesProvider());
    }

    /** {@inheritdoc} */
    public function boot(Application $app)
    {
    }
}