<?php
/**
 * Created by PhpStorm.
 * User: LetoAtreides
 * Date: 22.05.2016
 * Time: 15:27
 */

namespace HsBremen\WebApi\Module;


use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class ModuleRoutesProvider implements ControllerProviderInterface
{
    /** {@inheritdoc} */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        /**
         * @SWG\Parameter(
         *     name="id",
         *     type="integer",
         *     format="int32",
         *     in="path"
         * )
         * @SWG\Tag(
         *     name="module",
         *     description="All about modules"
         * )
         */

        /**
         * @SWG\Get(
         *     path="/module/",
         *     tags={"module"},
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource"
         *     )
         * )
         */
        $controllers->get('/', 'service.module:getList');

        /**
         * @SWG\Get(
         *     path="/module/{id}",
         *     tags={"module"},
         *     @SWG\Parameter(ref="#/parameters/id"),
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource",
         *          @SWG\Schema(ref="#/definitions/module")
         *     )
         * )
         */
        $controllers->get('/{moduleId}', 'service.module:getDetails');

        /**
         * @SWG\Post(
         *     tags={"module"},
         *     path="/module/",
         *     @SWG\Parameter(
         *         name="module",
         *         in="body",
         *         @SWG\Schema(ref="#/definitions/module")
         *     ),
         *     @SWG\Response(
         *         response="201",
         *         description="An example resource")
         *     )
         * )
         */
        $controllers->post('/', 'service.module:createModule');

        /**
         * @SWG\Put(
         *     tags={"module"},
         *     path="/module/{id}",
         *     @SWG\Parameter(ref="#/parameters/id"),
         *     @SWG\Response(
         *          response="200",
         *          description="An example resource",
         *          @SWG\Schema(ref="#/definitions/module")
         *     )
         * )
         */
        $controllers->put('/{moduleId}', 'service.module:changeModule');

        return $controllers;
    }
}