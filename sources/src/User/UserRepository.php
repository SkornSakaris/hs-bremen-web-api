<?php

namespace HsBremen\WebApi\User;

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Entity\User;

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

    public function getAll()
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

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }
}