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

        /**
         * @SWG\Tag(
         *     name="start",
         *     description="Startpage"
         * )
         */

        /**
         * @SWG\Get(
         *     path="/",
         *     tags={"start"},
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource"
         *     )
         * )
         */
        $controllers->get('/', 'service.start:getStart');

        /**
         * @SWG\Get(
         *     path="/login",
         *     tags={"start"},
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource"
         *     )
         * )
         */
        $controllers->get('/login', 'service.start:getLogin');

        /**
         * @SWG\Get(
         *     path="/registration",
         *     tags={"start"},
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource"
         *     )
         * )
         */
        $controllers->get('/registration', 'service.start:getRegistration');

        /**
         * @SWG\Get(
         *     path="/newmodule",
         *     tags={"start"},
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource"
         *     )
         * )
         */
        $controllers->get('/newmodule', 'service.start:getNewModule');

        return $controllers;
    }
}