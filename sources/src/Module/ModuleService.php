<?php

namespace HsBremen\WebApi\Module;

use HsBremen\WebApi\Entity\Module;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ModuleService
{

    /** @var  ModuleRepository */
    private $moduleRepository;

    /**
     * OrderService constructor.
     *
     * @param ModuleRepository $moduleRepository
     * @internal param ModuleRepository $orderRepository
     */
    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * GET /module
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList()
    {
        return new JsonResponse($this->moduleRepository->getAll());
    }

    /**
     * GET /module/{moduleId}
     *
     * @param $moduleId
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDetails($moduleId)
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
    public function createModule(Request $request)
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
    public function changeOrder(Request $request)
    {
        $module = new Module(1);
        $newId = $request->request->get('id', 0);
        $module->setId($newId);

        return new JsonResponse($module);
    }
}