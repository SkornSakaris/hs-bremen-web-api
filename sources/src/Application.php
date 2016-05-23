<?php

namespace HsBremen\WebApi;

use Basster\Silex\Provider\Swagger\SwaggerProvider;
use Basster\Silex\Provider\Swagger\SwaggerServiceKey;
use HsBremen\WebApi\Database\DatabaseProvider;
use HsBremen\WebApi\Module\ModuleServiceProvider;
use HsBremen\WebApi\Order\OrderServiceProvider;
use HsBremen\WebApi\Security\SecurityProvider;
use HsBremen\WebApi\Start\StartServiceProvider;
use JDesrosiers\Silex\Provider\CorsServiceProvider;
use Silex\Application as Silex;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Swagger\Annotations as SWG;
use SwaggerUI\Silex\Provider\SwaggerUIServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Application
 *
 * @package HsBremen\WebApi
 * @SWG\Swagger(
 *     consumes={"application/json"},
 *     produces={"application/json"},
 *     basePath="/",
 *     host="web-api.vm"
 * )
 * @SWG\Info(
 *     title="My First API",
 *     version="0.1"
 * )
 */
class Application extends Silex
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $app = $this;
        $app['base_path'] = __DIR__;

        // fuer die benutzten Funktionen benoetigte Silex-Provider
        $app->register(new ServiceControllerServiceProvider());
        $app->register(new UrlGeneratorServiceProvider());
        $app->register(new SessionServiceProvider());
        $app->register(new TwigServiceProvider(), array(
            'twig.path' => __DIR__ . '/../views',
        ));

        // fuer Swagger benoetigte Provider (inkl. after-Bedingung weiter unten)
        $app->register(new SwaggerProvider(),
                        [
                          SwaggerServiceKey::SWAGGER_SERVICE_PATH => $app['base_path'],
                          SwaggerServiceKey::SWAGGER_API_DOC_PATH => '/docs/swagger.json',
                        ]);
        $app->register(new SwaggerUIServiceProvider(),
                       [
                         'swaggerui.path' => '/docs/swagger',
                         'swaggerui.docs' => '/docs/swagger.json',
                       ]);
        $app->register(new CorsServiceProvider());

        // eigene Provider fuer die Datenbank und den Login
        $app->register(new DatabaseProvider());
        $app->register(new SecurityProvider());

        // eigene Provider fuer ID-Container, Routing, Funktionalitaet, etc.
        $app->register(new StartServiceProvider());
        $app->register(new OrderServiceProvider());
        $app->register(new ModuleServiceProvider());


        // http://silex.sensiolabs.org/doc/cookbook/json_request_body.html
        $app->before(function (Request $request) use ($app) {
            if ($app->requestIsJson($request)) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : []);
            }
        });

        $app->after($app['cors']);
    }

    private function requestIsJson(Request $request)
    {
        return 0 === strpos(
          $request->headers->get('Content-Type'),
          'application/json'
        );
    }
}
