<?php

namespace HsBremen\WebApi\User;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Swagger\Annotations as SWG;

class UserRoutesProvider implements ControllerProviderInterface
{
    /** {@inheritdoc} */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        /**
         * @SWG\Tag(
         *     name="user",
         *     description="All about users"
         * )
         */

        /**
         * @SWG\Get(
         *     path="/user",
         *     tags={"user"},
         *     @SWG\Response(
         *         response="200",
         *         description="An example resource",
         *         @SWG\Schema(
         *             type="array",
         *             @SWG\Items(
         *                 ref="#/definitions/user"
         *             )
         *         )
         *     )
         * )
         */
        $controllers->get('/', 'service.user:getAllUsers');

        /**
         * @SWG\Post(
         *     path="/user",
         *     tags={"user"},
         *     @SWG\Response(
         *         response="201",
         *         description="Neuer user wurde erstellt",
         *         @SWG\Schema(
         *             ref="#/definitions/user"
         *         )
         *     ),
         *     @SWG\Response(
         *         response="404",
         *         description="Seite konnte nicht gefunden werden"
         *     ),
         *     @SWG\Response(
         *         response="409",
         *         description="User existiert bereits"
         *     ),
         *     @SWG\Response(
         *         response="412",
         *         description="Eingabe nicht vollständig"
         *     )
         * )
         */
        $controllers->post('/', 'service.user:createNewUser');

        /**
         * @SWG\Get(
         *     path="/user/{id}",
         *     tags={"user"},
         *     @SWG\Response(
         *         response="200",
         *         description="User mit Id gefunden",
         *         @SWG\Schema(
         *             ref="#/definitions/user"
         *         )
         *     ),
         *     @SWG\Response(
         *         response="401",
         *         description="Keine Berechtigung User abzurufen"
         *     )
         * )
         */
        $controllers->get('/{userId}', 'service.user:getUserById');

        /**
         * @SWG\Put(
         *     path="/user/{id}",
         *     tags={"user"},
         *     @SWG\Response(
         *         response="201",
         *         description="User mit Id geändert",
         *         @SWG\Schema(
         *             ref="#/definitions/user"
         *         )
         *     ),
         *     @SWG\Response(
         *         response="401",
         *         description="Keine Berechtigung User zu ändern"
         *     )
         * )
         */
        $controllers->put('/{userId}', 'service.user:changeUserById');

        /**
         * @SWG\Delete(
         *     path="/user/{id}",
         *     tags={"user"},
         *     @SWG\Response(
         *         response="204",
         *         description="User mit Id gelöscht",
         *         @SWG\Schema(
         *             ref="#/definitions/user"
         *         )
         *     ),
         *     @SWG\Response(
         *         response="401",
         *         description="Keine Berechtigung User zu ändern"
         *     )
         * )
         */
        $controllers->delete('/{userId}', 'service.user:removeUserById');

        return $controllers;
    }
}