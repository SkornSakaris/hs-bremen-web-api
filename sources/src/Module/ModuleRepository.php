<?php

namespace HsBremen\WebApi\Module;

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Database\DatabaseException;
use HsBremen\WebApi\Entity\Module;

class ModuleRepository
{
    /**
     * @var Connection $connection
     */
    private $connection;

    /**
     * @var string $tableName
     */
    private $tableName = 'moduls';

    /**
     * ModuleRepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function createTable()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `{$this->getTableName()}` (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    generated BOOLEAN,
    code VARCHAR(5),
    shortname VARCHAR(10) NOT NULL,
    longname VARCHAR(50) NOT NULL,
    description TEXT,
    semester INT(1),
    ects INT(1),
    conditions VARCHAR(15)
)

EOS;
        return $this->connection->exec($sql);
    }

    public function createTestData(){
        $sql = <<<EOS
INSERT INTO `{$this->getTableName()}` VALUES(
    null, true, '1.1', 'MATHE1', 'Lineare Algebra', 'Mathe nervt', 1, 6, '-'
)
EOS;
        return $this->connection->exec($sql);
    }

    public function getAll()
    {
        $sql = <<<EOS
SELECT m.*
FROM `{$this->getTableName()}` m
EOS;

        $moduls = $this->connection->fetchAll($sql);

        $result = [];

        foreach ($moduls as $row) {
            $result[] = Module::createFromArray($row);
        }

        return $result;
    }

    public function getById($id)
    {
        $sql = <<<EOS
SELECT m.*
FROM `{$this->getTableName()}` m
WHERE m.id = :id
EOS;

        $moduls = $this->connection->fetchAll($sql, ['id' => $id]);
        if (count($moduls) === 0) {
            throw new DatabaseException(
                sprintf('Order with id "%d" not exists!', $id)
            );
        }

        return Module::createFromArray($moduls[0]);
    }

    public function save(Module $module)
    {
        $data = $module->jsonSerialize();
        unset($data['id']);

        $this->connection->insert("`{$this->getTableName()}`", $data);
        $module->setId($this->connection->lastInsertId());
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }



}