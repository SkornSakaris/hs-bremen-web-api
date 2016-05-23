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

class StartService
{
    public function getStart(Request $request, Application $app)
    {
        return $app['twig']->render('startpage.html.twig', array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }

    public function getLogin(Request $request, Application $app)
    {
        return $app['twig']->render('login.html.twig', array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
}