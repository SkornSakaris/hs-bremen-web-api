<?php

namespace HsBremen\WebApi\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class UserService
{

    /** @var  UserRepository $userRepository*/
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     * @internal param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserList()
    {
        return new JsonResponse($this->userRepository->getAll());
    }

    public function createNewUser()
    {

    }

    public function getUserById()
    {

    }

    public function changeUserById()
    {

    }

    public function deleteUserById()
    {

    }
}