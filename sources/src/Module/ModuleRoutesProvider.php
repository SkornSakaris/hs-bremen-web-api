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
         *         description="Listet alle Module des angemeldeten Benutzer auf"
         *     ),
         *     security={
         *         "modulmanager_auth": {"write:moduls", "read:moduls"}
         *     }
         * )
         */
        $controllers->get('/', 'service.module:getAllModuls');

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
         *         description="Neues Modul für den angemeldeten Benutzer erstellt")
         *     )
         * )
         */
        $controllers->post('/', 'service.module:createNewModule');

        /**
         * @SWG\Get(
         *     path="/module/{id}",
         *     tags={"module"},
         *     @SWG\Parameter(ref="#/parameters/id"),
         *     @SWG\Response(
         *         response="200",
         *         description="Öffnet das ausgewählte Modul des angemeldeten Benutzers",
         *         @SWG\Schema(ref="#/definitions/module")
         *     )
         * )
         */
        $controllers->get('/{moduleId}', 'service.module:getModuleById');

        /**
         * @SWG\Put(
         *     tags={"module"},
         *     path="/module/{id}",
         *     @SWG\Parameter(ref="#/parameters/id"),
         *     @SWG\Response(
         *          response="201",
         *          description="Änderungen am Modul des angemeldeten Benutzers erfolgreich",
         *          @SWG\Schema(ref="#/definitions/module")
         *     )
         * )
         */
        $controllers->put('/{moduleId}', 'service.module:changeModuleById');

        /**
         * @SWG\Delete(
         *     tags={"module"},
         *     path="/module/{id}",
         *     @SWG\Parameter(ref="#/parameters/id"),
         *     @SWG\Response(
         *          response="200",
         *          description="Löscht das ausgewählte Modul des angemeldeten Benutzers"
         *     )
         * )
         */
        $controllers->delete('/{moduleId}', 'service.module:removeModuleById');

        return $controllers;
    }
}