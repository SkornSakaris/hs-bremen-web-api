<?php

namespace HsBremen\WebApi\User;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class UserRoutesProvider implements ControllerProviderInterface
{
    /** {@inheritdoc} */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'service.user:getUserList');

        $controllers->post('/', 'service.user:createNewUser');

        $controllers->get('/{userId}', 'service.user:getUserById');

        $controllers->put('/{userId}', 'service.user:changeUserById');

        $controllers->delete('/{userId}', 'service.user:deleteUserById');

        return $controllers;
    }
}