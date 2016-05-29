<?php

namespace HsBremen\WebApi\User;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * @var string $templateName
     */
    private $templateName;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     * @internal param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->templateName = 'dummy.html.twig';
    }

    public function getAllUsers(Request $request, Application $app)
    {
        $result = null;
        $code = null;
        $data = null;

        $code = 200;
        $data['users'] = $this->userRepository->getAllUsers();

        return $this->createResponse($code, $data, $request, $app);
    }

    public function getUserById($userId, Request $request, Application $app)
    {
        $result = null;
        $code = null;
        $data = null;

        $code = 200;
        $data['user'] = $this->userRepository->getUserById($userId);

    return $this->createResponse($code, $data, $request, $app);
    }

    public function createNewUser(Request $request, Application $app)
    {
        $code = null;
        $data = null;
        $postData = null;
        $validatedData = null;

        $postData = $request->request->all();
        $validatedData = $this->validateNewUser($postData);

        if ($validatedData['code'] === 201)
        {
            $code = $validatedData['code'];
            $data['user'] = $this->userRepository->insertNewUserAndReturn($validatedData['user']);
        }
        else
        {
            $code = $validatedData['code'];
            $data['error'] = $validatedData['error'];
        }

        $response = $this->createResponse($code, $data, $request, $app);

        return $response;
    }

    public function changeUserById($userId, Request $request, Application $app)
    {
        $code = null;
        $data = null;
        $postData = null;
        $validatedData = null;

        $postData = $request->request->all();
        $postData['id'] = $userId;
        $validatedData = $this->validateChangeUser($postData);

        if ($validatedData['code'] === 201)
        {
            $code = $validatedData['code'];
            $data['user'] = $this->userRepository->updateUserByIdAndReturn($validatedData['user']);
        }
        else
        {
            $code = $validatedData['code'];
            $data = $validatedData['error'];
        }

        return $this->createResponse($code, $data, $request, $app);
    }

    public function removeUserById($userId, Request $request, Application $app)
    {
        $result = null;
        $code = null;
        $data = null;

        $code = 201;
        $data['message'] = "Benutzer mit der Id: $userId wurde erfolgreich gelöscht";
        $this->userRepository->deleteUserById($userId);

        return $this->createResponse($code, $data, $request, $app);
    }

    /**
     * Überprüfung ob notwendige Eingabeparameter vorhanden -> sonst Fehlercode und Error
     * Überprüfung ob Passwortwiederholung indentisch -> sons Fehlercoe und Error
     * Selektierung der für die 'createNewUser' notwendigen Parameter
     *
     * @param $postData
     *
     * @return null
     */
    public function validateNewUser($postData)
    {
        $result = null;

        if (false === array_key_exists('username', $postData))
        {
            $result['code'] = 412;
            $result['error'] = "Der Eingabeparameter 'username' ist nicht angegeben";
        }
        elseif (false === array_key_exists('password', $postData))
        {
            $result['code'] = 412;
            $result['error'] = "Der Eingabeparameter 'password' ist nicht angegeben";
        }
        elseif (false === array_key_exists('passwordConf', $postData))
        {
            $result['code'] = 412;
            $result['error'] = "Der Eingabeparameter 'passwordConf' ist nicht angegeben";
        }
        elseif ($postData['password'] !== $postData['passwordConf'])
        {
            $result['code']  = 412;
            $result['error'] = "Die Eingabeparamter 'password' und 'passwordConf' stimmen nicht überein";
        }
        else
        {
            if (count($postData))
            {
                $result['warning'] = "Es werden unnötige Eingabeparameter angegeben (werden ignoriert)";
            }
            $result['code']  = 201;
            $result['user']['username'] = $postData['username'];
            $result['user']['password'] = $postData['password'];
            $result['user']['roles'] = 'ROLE_USER';
        }

        return $result;
    }

    public function validateChangeUser($postData)
    {
        $result = null;

        $result['code'] = 201;

        if (array_key_exists('id', $postData))
        {
            $result['user']['id'] = $postData['id'];
        }
        if (array_key_exists('newUsername', $postData))
        {
            $result['user']['username'] = $postData['newUsername'];
        }
        if (array_key_exists('newPassword', $postData) && array_key_exists('newPasswordConf', $postData))
        {
            if ($postData['newPassword'] !== $postData['newPasswordConf'])
            {
                $result['code'] = 412;
                $result['error'] = "Passwörter stimmen nicht überein";
            }
            else
            {
                $result['user']['password'] = $postData['newPassword'];
            }
        }
        elseif (false === array_key_exists('newPassword', $postData) && array_key_exists('newPasswordConf', $postData))
        {
            $result['code'] = 412;
            $result['error'] = "Eingabe für Passwort fehlt";

        }
        elseif (array_key_exists('newPassword', $postData) && false === array_key_exists('newPasswordConf', $postData))
        {
            $result['code'] = 412;
            $result['error'] = "Eingabe für Passwortwiederholung fehlt";
        }

        return $result;
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