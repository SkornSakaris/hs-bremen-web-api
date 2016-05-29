<?php

namespace HsBremen\WebApi\Module;

use HsBremen\WebApi\Entity\Module;
use HsBremen\WebApi\Entity\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
//        $this->templateName = 'dummy.html.twig';
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
        $data = $this->moduleRepository->getAllModuls($userId);

        if (0 === strpos($request->headers->get('Accept'), 'application/json'))
        {
            $result = new JsonResponse($data, 200);
        }
        else
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
        $data = $this->moduleRepository->getModuleById($userId, $moduleId);

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
        $userId = null;
        $data = null;
        $code = null;
        $validatedData = null;

        $userId = $this->getUserIdFromToken($app);

        $postData = $request->request->all();
        unset($postData['id']);

        $validatedData = $this->validateNewModule($postData);

        if ($validatedData['code'] === 201)
        {
            $code = $validatedData['code'];
            $data['user'] = $this->moduleRepository->insertModuleAndReturn($userId, $validatedData['module']);
        }
        else
        {
            $code = $validatedData['code'];
            $data['error'] = $validatedData['error'];
        }


        $response = $this->createResponse($code, $data, $request, $app);

        return $response;
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
        $data = $this->moduleRepository->updateModuleByIdAndReturn($userId, $module);

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

    public function validateNewModule($postData)
    {
        $result = null;

        // notwendige Parameter zum Anlegen eines neuen Moduls in der Datenbank
        if (false === array_key_exists('shortname', $postData))
        {
            $result['code'] = 412;
            $result['error'] = "Der Eingabeparameter 'shortname' für Modul-Kürzel ist nicht angegeben (erforderlich)";
        }
        elseif (false === array_key_exists('longname', $postData))
        {
            $result['code'] = 412;
            $result['error'] = "Der Eingabeparameter 'longname' für Modul-Bezeichnung st nicht angegeben (erforderlich)";
        }
        else
        {
            $result['code']  = 201;
            $result['module']['generated'] = 'true';
            $result['module']['shortname'] = $postData['shortname'];
            $result['module']['longname'] = $postData['longname'];
        }

        // optional, aber relevante Parameter zum Anlegen eines neuen Moduls in der Datenbank
        if (false === array_key_exists('code', $postData))
        {
            $result['warning'][] = "Der Eingabeparameter 'code' für Modul-Code ist nicht angegeben (optional)";
            $result['module']['code'] = '';
        }
        elseif (false === array_key_exists('description', $postData))
        {
            $result['warning'][] = "Der Eingabeparameter 'description' für Modul-Beschreibung ist nicht angegeben (optional)";
            $result['module']['description'] = '';
        }
        elseif (false === array_key_exists('semester', $postData))
        {
            $result['warning'][] = "Der Eingabeparameter 'semester' für Modul-Semester ist nicht angegeben (optional)";
            $result['module']['semester'] = '';
        }
        elseif (false === array_key_exists('ects', $postData))
        {
            $result['warning'][] = "Der Eingabeparameter 'ects' für Modul-ECTS ist nicht angegeben (optional)";
            $result['module']['ects'] = '';
        }
        elseif (false === array_key_exists('conditions', $postData))
        {
            $result['warning'][] = "Der Eingabeparameter 'conditions' für Modul-Vorraussetzung ist nicht angegeben (optional)";
            $result['module']['conditions'] = '';
        }
        elseif (false === array_key_exists('lecturer', $postData))
        {
            $result['warning'][] = "Der Eingabeparameter 'lecturer' für Modul-Dozent ist nicht angegeben (optional)";
            $result['module']['lecturer'] = '';
        }
        elseif (false === array_key_exists('attempt', $postData))
        {
            $result['warning'][] = "Der Eingabeparameter 'attempt' für Modul-Versuch ist nicht angegeben (optional)";
            $result['module']['attempt'] = '';
        }
        elseif (false === array_key_exists('grade', $postData))
        {
            $result['warning'][] = "Der Eingabeparameter 'grade' für Modul-Note ist nicht angegeben (optional)";
            $result['module']['grade'] = '';
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
        /**
         * @var TokenInterface $token
         * @var User $user
         */
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

    public function createResponse($code, $data, Request $request, Application $app)
    {
        if (0 === strpos($request->headers->get('Accept'), 'application/json'))
        {
            $response = new JsonResponse($data, $code);
        }
        else
        {
            $response = new Response($app['twig']->render($this->templateName, $data), $code);
        }

        return $response;
    }
}