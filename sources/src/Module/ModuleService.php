<?php

namespace HsBremen\WebApi\Module;

use HsBremen\WebApi\Entity\Module;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleService
{
    /**
     * @var ModuleRepository $moduleRepository
     */
    private $moduleRepository;

    /**
     * @var string $templateName
     */
    private $templateName;

    /**
     * OrderService constructor.
     *
     * @param ModuleRepository $moduleRepository
     * @internal param ModuleRepository $orderRepository
     */
    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
        $this->templateName = 'module.html.twig';
    }

    /**
     * GET /module
     *
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     */
    public function getAllModuls(Request $request, Application $app)
    {
//        $token = $app['security.token_storage']->getToken();
//        if (null !== $token) {
//            $user = $token->getUser();
//        }
//        echo $user . '<br>';
//        var_dump($token);

        $result = null;
        $data = $this->moduleRepository->getAll();

        if (0 === strpos($request->headers->get('Accept'), 'application/json'))
        {
            $result = new JsonResponse($data, 200);
        }
        if (0 === strpos($request->headers->get('Accept'), 'text/html'))
        {
            $result = new Response($app['twig']->render($this->templateName, $data), 200);
        }

        return $result;
    }

    /**
     * GET /module/{moduleId}
     *
     * @param $moduleId
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getModuleById($moduleId)
    {
        return new JsonResponse($this->moduleRepository->getById($moduleId));
    }

    /**
     * POST /module
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createNewModule(Request $request)
    {
        $postData = $request->request->all();
        unset($postData['id']);

        $module = Module::createFromArray($postData);

        $this->moduleRepository->save($module);

        return new JsonResponse($module, 201);
    }

    /**
     * PUT /module/{moduleId}
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function changeModuleById(Request $request)
    {
        $module = new Module(1);
        $newId = $request->request->get('id', 0);
        $module->setId($newId);

        return new JsonResponse($module);
    }
}