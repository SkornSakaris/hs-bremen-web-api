<?php
/**
 * Created by PhpStorm.
 * User: LetoAtreides
 * Date: 28.05.2016
 * Time: 16:45
 */

namespace HsBremen\WebApi\Tests\User;


use Doctrine\DBAL\Connection;
use HsBremen\WebApi\User\UserRepository;
use HsBremen\WebApi\User\UserService;

class UserServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var
     * \Doctrine\DBAL\Connection|\PHPUnit_Framework_MockObject_MockObject $db
     */
    private $connection;

    /**
     * @var  UserRepository
     */
    private $repository;

    /**
     * @var UserService
     */
    private $userService;

    public function setUp()
    {
        $this->connection = self::getMockBuilder(Connection::class)
                                ->disableOriginalConstructor()
                                ->getMock()
        ;

        $this->repository = self::getMockBuilder(UserRepository::class)
                                ->disableOriginalConstructor()
                                ->getMock()
        ;

        $this->userService = new UserService($this->repository);
    }

    /**
     * @test
     */
    public function shouldBeOk_newUser_correctData()
    {
        $postData = [
            'username' => 'Mustermann',
            'password' => 'muster',
            'passwordConf' => 'muster',
        ];

        $result = $this->userService->validateNewUser($postData);

        self::assertArrayHasKey('code', $result);

        self::assertEquals($result['code'], 201);
    }

    /**
     * @test
     */
    public function shouldBeWarning_newUser_toMuchData()
    {
        $postData = [
            'test' => 'unnötig',
            'username' => 'Mustermann',
            'password' => 'muster',
            'passwordConf' => 'muster',
        ];

        $result = $this->userService->validateNewUser($postData);

        self::assertArrayHasKey('code', $result);
        self::assertArrayHasKey('warning', $result);

        self::assertEquals($result['code'], 201);
        self::assertEquals($result['warning'], "Es werden unnötige Eingabeparameter angegeben (werden ignoriert)");
    }

    /**
     * @test
     */
    public function shouldBeError_newUser_missingUsername()
    {
        $postData = [
            'password' => 'muster',
            'passwordConf' => 'muster',
        ];

        $result = $this->userService->validateNewUser($postData);

        self::assertArrayHasKey('code', $result);
        self::assertArrayHasKey('error', $result);

        self::assertEquals($result['code'], 412);
        self::assertEquals($result['error'], "Der Eingabeparameter 'username' ist nicht angegeben");
    }

    /**
     * @test
     */
    public function shouldBeError_newUser_missingPassword()
    {
        $postData = [
            'username' => 'Mustermann',
            'passwordConf' => 'muster',
        ];

        $result = $this->userService->validateNewUser($postData);

        self::assertArrayHasKey('code', $result);
        self::assertArrayHasKey('error', $result);

        self::assertEquals($result['code'], 412);
        self::assertEquals($result['error'], "Der Eingabeparameter 'password' ist nicht angegeben");
    }

    /**
     * @test
     */
    public function shouldBeError_newUser_missingPasswordConf()
    {
        $postData = [
            'username' => 'Mustermann',
            'password' => 'muster',
        ];

        $result = $this->userService->validateNewUser($postData);

        self::assertArrayHasKey('code', $result);
        self::assertArrayHasKey('error', $result);

        self::assertEquals($result['code'], 412);
        self::assertEquals($result['error'], "Der Eingabeparameter 'passwordConf' ist nicht angegeben");
    }

    /**
     * @test
     */
    public function shouldBeError_newUser_wrongPassword()
    {
        $postData = [
            'username' => 'Mustermann',
            'password' => 'muster',
            'passwordConf' => 'fehler',
        ];

        $result = $this->userService->validateNewUser($postData);

        self::assertArrayHasKey('code', $result);
        self::assertArrayHasKey('error', $result);

        self::assertEquals($result['code'], 412);
        self::assertEquals($result['error'], "Die Eingabeparamter 'password' und 'passwordConf' stimmen nicht überein");
    }



}
