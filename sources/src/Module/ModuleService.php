<?php

namespace HsBremen\WebApi\Module;

use HsBremen\WebApi\Entity\Module;
use HsBremen\WebApi\Entity\User;
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
//        $this->templateName = 'module.html.twig';
        $this->templateName = 'dummy.html.twig';
    }

    /**
     * GET /module
     *
     * @param Request $request
     * @param Application $app
     *
     * @return JsonResponse
     */
    public function getAllModuls(Request $request, Application $app)
    {
        $result = null;
        $userId = $this->getUserIdFromToken($app);
        $data = $this->moduleRepository->getAll($userId);

        if (0 === strpos($request->headers->get('Accept'), 'application/json'))
        {
            $result = new JsonResponse($data, 200);
        }
        elseif (0 === strpos($request->headers->get('Accept'), 'text/html'))
        {
            $sortedData['moduls'] = $this->sortResultForTemplate($data);
            $sortedData['quantity'] = count($data);
            $result = new Response($app['twig']->render($this->templateName, $sortedData), 200);
        }

        return $result;
    }

    /**
     * GET /module/{moduleId}
     *
     * @param $moduleId
     * @param Request $request
     * @param Application $app
     *
     * @return JsonResponse
     */
    public function getModuleById($moduleId, Request $request, Application $app)
    {
        $result = null;

        $userId = $this->getUserIdFromToken($app);
        $data = $this->moduleRepository->getById($userId, $moduleId);

        if (0 === strpos($request->headers->get('Accept'), 'application/json'))
        {
            $result = new JsonResponse($data, 200);
        }
        else
        {
            $sortedData['module'] = $data;
            $result = new Response($app['twig']->render($this->templateName, $sortedData), 200);
        }

        return $result;
    }

    /**
     * POST /module
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Application $app
     *
     * @return JsonResponse
     */
    public function createNewModule(Request $request, Application $app)
    {
        $result = null;
        $userId = $this->getUserIdFromToken($app);

        $postData = $request->request->all();
        unset($postData['id']);

        $module = new Module($postData);
        $data = $this->moduleRepository->insertModuleAndReturn($userId, $module);

        if (0 === strpos($request->headers->get('Accept'), 'application/json'))
        {
            $result = new JsonResponse($data, 201);
        }
        else
        {
            $sortedData['module'] = $data;
            $result = new Response($app['twig']->render($this->templateName, $sortedData), 201);
        }

        return $result;
    }

    /**
     * PUT /module/{moduleId}
     *
     * @param int $moduleId
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Application $app
     *
     * @return JsonResponse
     */
    public function changeModuleById($moduleId, Request $request, Application $app)
    {
        $result = null;
        $userId = $this->getUserIdFromToken($app);

        $postData = $request->request->all();
        $postData['id'] = $moduleId;

        $module = new Module($postData);
        $data = $this->moduleRepository->updateModuleById($userId, $module);

        if (0 === strpos($request->headers->get('Accept'), 'application/json'))
        {
            $result = new JsonResponse($data, 201);
        }
        else
        {
            $sortedData['module'] = $data;
            $result = new Response($app['twig']->render($this->templateName, $sortedData), 201);
        }

        return $result;
    }

    /**
     * DELETE /module/[moduleId}
     *
     * @param $moduleId
     * @param Request $request
     * @param Application $app
     *
     * @return null|JsonResponse|Response
     */
    public function removeModuleById($moduleId, Request $request, Application $app)
    {
        $result = null;

        $data = $this->moduleRepository->deleteModuleById($moduleId);

        if (0 === strpos($request->headers->get('Accept'), 'application/json'))
        {
            $result = new JsonResponse($data, 201);
        }
        else
        {
            $sortedData['module'] = $data;
            $result = new Response($app['twig']->render($this->templateName, $sortedData), 201);
        }

        return $result;
    }

    /**
     * Gibt die Id des angemeldeten Benutzers zurück
     *
     * @param Application $app
     *
     * @return mixed
     */
    private function getUserIdFromToken(Application $app)
    {
        $token = $app['security.token_storage']->getToken();
        if (null !== $token) {
            $user = $token->getUser();
        }
        return $user->getID();
    }

    /**
     * @param Module[] $data
     * @return null
     */
    private function sortResultForTemplate($data)
    {
        $sortedData = null;

        foreach ($data as $module)
        {
            $sortedData[$module->getSemester()][] = $module;
        }

        return $sortedData;
    }
}