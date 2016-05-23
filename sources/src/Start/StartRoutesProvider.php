<?php

namespace HsBremen\WebApi\Start;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class StartRoutesProvider implements ControllerProviderInterface
{
    /** {@inheritdoc} */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'service.start:getStart');
        $controllers->get('/login', 'service.start:getLogin');

        return $controllers;
    }
}