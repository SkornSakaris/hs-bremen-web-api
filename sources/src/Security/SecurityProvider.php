<?php

namespace HsBremen\WebApi\Security;

use HsBremen\WebApi\User\UserProvider;
use Silex\Application;
use Silex\Provider\SecurityServiceProvider;
use Silex\ServiceProviderInterface;



/**
 * Class SecurityProvider
 * @package HsBremen\WebApi\Security
 *
 * @SWG\SecurityScheme(
 *     securityDefinition="jsonWebToken",
 *     type="apiKey",
 *     in="header",
 *     name="PHPSESSID"
 * )
 *
 * @SWG\SecurityScheme(
 *     securityDefinition="modulmanager_auth",
 *     type="oauth2",
 *     authorizationUrl="http://web-api.vm/login",
 *     flow="implicit",
 *     scopes={
 *         "read:modules" : "read your moduls",
 *         "write:modules" : "modify moduls in your account"
 *     }
 * )
 */
class SecurityProvider implements ServiceProviderInterface
{

    /** {@inheritdoc} */
    public function register(Application $app)
    {
        $app->register(new SecurityServiceProvider(), array(
            'security.firewalls' => array(
                'login_path' => array(
                    'pattern' => '^/login$',
                    'anonymous' => true
                ),
                'swagger' => array(
                    'pattern' => '^/docs.*$',
                    'anonymous' => true
                ),
                'secured' => array(
                    'pattern' => '^/.*$',
                    'anonymous' => true,
                    'form' => array(
                        'login_path' => '/login',
                        'check_path' => '/login_check',
                    ),
                    'logout' => array(
                        'logout_path' => '/logout',
                        'invalidate_session' => false
                    ),
                    'users' => $app->share(function($app) {
                        return new UserProvider($app['db']);
                    }),
                )
            ),
            'security.access_rules' => array(
                array('^/login$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
                array('^/docs.+$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
                array('^/.+$', 'ROLE_USER'),
            )
        ));

//        $app->register(new SecurityServiceProvider());

//        $app['security.firewalls'] = [
//          'admin' => [
//              // RegEx
//              'pattern' => '^/',
//              // HTTP-Basic Auth flag
//              'http'    => true,
//              // Users array
//              'users'   => [
//                  // raw password is foo
//                  'admin' => [
//                    'ROLE_ADMIN',
//                    '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
//                  ],
//              ],
//          ],
//        ];
    }

    /** {@inheritdoc} */
    public function boot(Application $app)
    {
    }
}
