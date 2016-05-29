<?php
/**
 * Created by PhpStorm.
 * User: LetoAtreides
 * Date: 23.05.2016
 * Time: 11:18
 */

namespace HsBremen\WebApi\Start;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StartService
{
    public function getStart(Request $request, Application $app)
    {
        return new Response(
            $app['twig']->render(
                'startpage.html.twig', array(
                    'error' => $app['security.last_error']($request),
                    'last_username' => $app['session']->get('_security.last_username'),
                    )
                ),
            200
        );
    }

    public function getLogin(Request $request, Application $app)
    {
        return new Response(
            $app['twig']->render(
                'login.html.twig', array(
                    'error' => $app['security.last_error']($request),
                    'last_username' => $app['session']->get('_security.last_username'),
                )
            ),
            200
        );
    }

    public function getRegistration(Request $request, Application $app)
    {
        return new Response(
            $app['twig']->render(
                'registration.html.twig', array(
                    'error' => $app['security.last_error']($request),
                    'last_username' => $app['session']->get('_security.last_username'),
                )
            ),
            200
        );
    }

    public function getNewModule(Request $request, Application $app)
    {
        return new Response(
            $app['twig']->render(
                'newmodule.html.twig', array(
                    'error' => $app['security.last_error']($request),
                    'last_username' => $app['session']->get('_security.last_username'),
                )
            ),
            200
        );
    }
}