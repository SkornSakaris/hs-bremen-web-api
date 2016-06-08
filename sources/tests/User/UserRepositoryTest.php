<?php

namespace HsBremen\WebApi\Tests\User;


use HsBremen\WebApi\Database\DatabaseException;
use HsBremen\WebApi\Entity\User;
use HsBremen\WebApi\User\UserRepository;

class UserRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Doctrine\DBAL\Connection|\PHPUnit_Framework_MockObject_MockObject $db */
    private $db;

    /** @var  UserRepository */
    private $repository;

    public function setUp()
    {
        $this->db = self::getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->repository = new UserRepository($this->db);
    }
    /**
     * @test
     */
    public function shouldReturnTableName()
    {
        self::assertEquals('users', $this->repository->getTableName());
    }

    /**
     * @test
     */
    public function shouldDropTable()
    {
        $sql = "DROP TABLE IF EXISTS `users`";

        $this->db->expects(self::once())
            ->method('exec')
            ->with($sql)
        ;

        $this->repository->dropTable();
    }

    /**
     * @test
     */
    public function shouldCreateTable()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `users` (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    salt VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    roles VARCHAR(255) DEFAULT 'ROLE_USER' NOT NULL
)
EOS;
        $this->db->expects(self::once())
            ->method('exec')
            ->with($sql)
        ;

        $this->repository->createTable();
    }

    /**
     * @test
     */
    public function shouldCreateTestData()
    {
        $sql[0] = "INSERT INTO `users` VALUES(null, 'Jannik', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[1] = "INSERT INTO `users` VALUES(null, 'Andi', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[2] = "INSERT INTO `users` VALUES(null, 'Nils', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[3] = "INSERT INTO `users` VALUES(null, 'Ole', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";

        $sql[4] = "INSERT INTO `users` VALUES(null, 'Mister 5', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[5] = "INSERT INTO `users` VALUES(null, 'Mister 6', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[6] = "INSERT INTO `users` VALUES(null, 'Mister 7', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[7] = "INSERT INTO `users` VALUES(null, 'Mister 8', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[8] = "INSERT INTO `users` VALUES(null, 'Mister 9', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[9] = "INSERT INTO `users` VALUES(null, 'Mister 10', '', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";

        $this->db->expects(self::exactly(10))
            ->method('exec')
//            ->with($this->isType('string'))
            ->withConsecutive(
                [$sql[0]],
                [$sql[1]],
                [$sql[2]],
                [$sql[3]],
                [$sql[4]],
                [$sql[5]],
                [$sql[6]],
                [$sql[7]],
                [$sql[8]],
                [$sql[9]]
            )
        ;

        $this->repository->createTestData();
    }

    /**
     * @test
     */
    public function shouldDropTableUsersToModuls()
    {
        $sql = "DROP TABLE IF EXISTS `users_moduls`";

        $this->db->expects(self::once())
            ->method('exec')
            ->with($sql)
        ;

        $this->repository->dropTableUsersToModuls();
    }

    /**
     * @test
     */
    public function shouldCreateTableUsersToModuls()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `users_moduls` (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    module_id INT(11) NOT NULL,
    lecturer VARCHAR(255),
    attempt INT(1),
    grade FLOAT(1)
)
EOS;
        $this->db->expects(self::once())
            ->method('exec')
            ->with($sql)
        ;

        $this->repository->createTableUsersToModuls();
    }

    /**
     * @test
     */
    public function shouldCreateTestDataUsersToModuls()
    {
        $this->db->expects(self::exactly(30))
            ->method('exec')
            ->with($this->isType('string'))
        ;

        $this->repository->createTestDataUsersToModuls();
    }

    /**
     * @test
     */
    public function shouldGetAllUsers()
    {
        $userData[0] = [
            'id' => 100,
            'username' => 'Testname',
            'salt' => '',
            'password' => 123456,
            'roles' => 'ROLE_USER',
        ];

        $sql = <<<EOS
SELECT u.*
FROM `users` u
EOS;
        $this->db->expects(self::once())
            ->method('fetchAll')
            ->with($sql)
            ->willReturn($userData)
        ;

        /**
         * @var User[] $result
         */
        $result = $this->repository->getAllUsers();

        self::assertInstanceOf(User::class, $result[0]);
        self::assertEquals(100, $result[0]->getId());
        self::assertEquals('Testname', $result[0]->getUsername());
        self::assertEquals('', $result[0]->getSalt());
        self::assertEquals(123456, $result[0]->getPassword());
        self::assertEquals(['ROLE_USER'], $result[0]->getRoles());
    }

    /**
     * @test
     */
    public function shouldGetUserById()
    {
        $userData[0] = [
            'id' => 100,
            'username' => 'Testname',
            'salt' => '',
            'password' => 123456,
            'roles' => 'ROLE_USER',
        ];

        $sql = <<<EOS
SELECT u.*
FROM `users` u
WHERE u.id = :id
EOS;
        $this->db->expects(self::once())
            ->method('fetchAll')
            ->with($sql)
            ->willReturn($userData)
        ;

        /**
         * @var User $result
         */
        $result = $this->repository->getUserById(100);

        self::assertInstanceOf(User::class, $result);
        self::assertEquals(100, $result->getId());
        self::assertEquals('Testname', $result->getUsername());
        self::assertEquals('', $result->getSalt());
        self::assertEquals(123456, $result->getPassword());
        self::assertEquals(['ROLE_USER'], $result->getRoles());
    }

    /**
     * @test
     *
     * @expectedException \HsBremen\WebApi\Database\DatabaseException
     * @expectedExceptionMessage User with id "100" not exists!
     */
    public function shouldThrowExceptionUserNotFound()
    {
        $userData = [];

        $sql = <<<EOS
SELECT u.*
FROM `users` u
WHERE u.id = :id
EOS;
        $this->db->expects(self::once())
            ->method('fetchAll')
            ->with($sql)
            ->willReturn($userData)
        ;

        /**
         * @var User $result
         */
        $this->repository->getUserById(100);
    }

    /**
     * @test
     */
    public function shouldInsertNewUserAndReturn()
    {
        $userData = [
            'username' => 'Testname',
            'password' => 'foo',
            'passwordConf' => 'foo',
            'roles' => 'ROLE_USER',
        ];

        $preparedUserData = [
            'username' => 'Testname',
            'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
            'roles' => 'ROLE_USER',
        ];

        $this->db->expects(self::once())
            ->method('insert')
            ->with('`users`', $preparedUserData)
        ;

        $this->db->expects(self::once())
            ->method('lastInsertId')
            ->willReturn(100)
        ;

        $result = $this->repository->insertNewUserAndReturn($userData);

        self::assertInstanceOf(User::class, $result);
        self::assertEquals(100, $result->getId());
        self::assertEquals('Testname', $result->getUsername());
        self::assertEquals('', $result->getSalt());
        self::assertEquals('5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', $result->getPassword());
        self::assertEquals(['ROLE_USER'], $result->getRoles());
    }

    /**
     * @test
     */
    public function shouldUpdateUserByIdAndReturn()
    {
        $userId = ['id' => 100];

        $userData = [
            'id' => '100',
            'username' => 'Testname',
            'password' => 'foo',
        ];

        $preparedUserData = [
            'username' => 'Testname',
            'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
        ];

        $expectedUserData[] = [
            'id' => '100',
            'username' => 'Testname',
            'salt' => '',
            'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
            'roles' => 'ROLE_USER',
        ];

        $this->db->expects(self::once())
            ->method('update')
            ->with('`users`', $preparedUserData, $userId)
        ;

        $sql = <<<EOS
SELECT u.*
FROM `users` u
WHERE u.id = :id
EOS;
        $this->db->expects(self::once())
            ->method('fetchAll')
            ->with($sql)
            ->willReturn($expectedUserData)
        ;

        $result = $this->repository->updateUserByIdAndReturn($userData);

        self::assertInstanceOf(User::class, $result);
        self::assertEquals(100, $result->getId());
        self::assertEquals('Testname', $result->getUsername());
        self::assertEquals('', $result->getSalt());
        self::assertEquals('5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', $result->getPassword());
        self::assertEquals(['ROLE_USER'], $result->getRoles());
    }

    /**
     * @test
     */
    public function shouldDeleteUserById()
    {
        $userId = 100;

        $this->db->expects(self::once())
            ->method('delete')
            ->with('`users`', ['id' => $userId])
        ;

        $this->repository->deleteUserById($userId);
    }
}
