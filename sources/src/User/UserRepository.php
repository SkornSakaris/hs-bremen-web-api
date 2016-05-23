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
        $sql = "DROP TABLE `{$this->getTableName()}`";
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
        $sql = "INSERT INTO `{$this->getTableName()}` VALUES(null, 'Jannik', '','5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $this->connection->exec($sql);
        $sql = "INSERT INTO `{$this->getTableName()}` VALUES(null, 'Andi', '','5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $this->connection->exec($sql);
        $sql = "INSERT INTO `{$this->getTableName()}` VALUES(null, 'Nils', '','5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==', 'ROLE_USER')";
        $this->connection->exec($sql);
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }
}