<?php

namespace HsBremen\WebApi\User;

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Database\DatabaseException;
use HsBremen\WebApi\Entity\User;
use Silex\Application;

class UserRepository
{
    /**
     * @var Connection $connection
     */
    private $connection;

    /**
     * @var string $tableName
     */
    private $tableName = 'users';

    /**
     * ModuleRepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function dropTable()
    {
        $sql = "DROP TABLE `{$this->getTableName()}` IF EXISTS";
        $this->connection->exec($sql);
    }

    public function createTable()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `{$this->getTableName()}` (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    salt VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    roles VARCHAR(255) NOT NULL
)
EOS;
        $this->connection->exec($sql);
    }

    public function createTestData()
    {
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, 'Jannik', '','5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, 'Andi', '','5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, 'Nils', '','5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, 'Ole', '','5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";

        foreach ($sql as $query)
        {
            $this->connection->exec($query);
        }
    }

    public function createTableUsersToModuls()
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
        $this->connection->exec($sql);
    }

    public function dropTableUsersToModuls()
    {
        $sql = "DROP TABLE `users_moduls` IF EXISTS";
        $this->connection->exec($sql);
    }

    public function createTestDataUsersToModuls()
    {
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 1, 'Herr Krug', 1, 3.7)";
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 2, 'Herr Trittin', 1, 3.5)";
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 3, 'Herr Mosemann', 1, 1.3)";
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 4, 'Herr Mosemann', 1, 2.7)";
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 5, 'Herr Unknown', 1, 2.0)";

        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 6, 'Herr Mevenkamp', 1, 3.7)";
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 7, 'Herr Assmann', 1, 3.3)";
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 8, 'Herr LilaRucksack', 1, 2.7)";
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 9, 'Herr Mosemann', 1, 2.7)";
        $sql[] = "INSERT INTO `users_moduls` VALUES(null, 1, 10, 'Herr Breyer', 1, 4.0)";

        foreach ($sql as $query)
        {
            $this->connection->exec($query);
        }
    }

    public function getAllUsers()
    {
        $sql = <<<EOS
SELECT u.*
FROM `{$this->getTableName()}` u
EOS;

        $users = $this->connection->fetchAll($sql);

        $result = [];

        foreach ($users as $row) {
            $result[] = new User($row['id'], $row['username'], $row['password'], explode(',', $row['roles']), true, true, true, true);
        }

        return $result;
    }

    public function getUserById($userId)
    {
        $sql = <<<EOS
SELECT u.*
FROM `{$this->getTableName()}` u
WHERE u.id = :id
EOS;
        $users = $this->connection->fetchAll($sql, ['id' => $userId]);

        if (count($users) === 0) {
            throw new DatabaseException(
                sprintf('User with id "%d" not exists!', $userId)
            );
        }

        $userData = $users[0];

        return new User($userData['id'], $userData['username'], $userData['password'], explode(',', $userData['roles']), true, true, true, true);
    }


    /**
     * @param array $userData
     * @return User
     */
    public function insertNewUserAndReturn($userData)
    {
        unset($userData['passwordConf']);
        $this->connection->insert("`{$this->getTableName()}`", $userData);

        $userId = $this->connection->lastInsertId();
        $userData['id'] = $userId;
        $userData['roles'] = 'USER_ROLE';

        return new User($userData['id'], $userData['username'], $userData['password'], explode(',', $userData['roles']), true, true, true, true);
    }

    public function updateUserByIdAndReturn($userData)
    {
        $userId = $userData['id'];
        unset($userData['id']);

        $this->connection->update("`{$this->getTableName()}`", $userData, ['id' => $userId]);

        $user = $this->getUserById($userId);

        return new User($user->getId(), $user->getUsername(), $user->getPassword(), $user->getRoles(), true, true, true, true);
    }

    public function deleteUserById($userId)
    {

    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }


}