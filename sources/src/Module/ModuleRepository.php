<?php

namespace HsBremen\WebApi\Module;

use Doctrine\DBAL\Driver\Connection;

class ModuleRepository
{
    /** @var  Connection */
    private $connection;

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
CREATE TALBE IF NOT EXISTS moduls (
    id_modul INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    generated BOOLEAN,
    code VARCHAR(5),
    shortname VARCHAR(10) NOT NULL,
    longname VARCHAR(50) NOT NULL,
    description TEXT,
    semester INT(1),
    ects INT(1),
    conditions VARCHAR(15),
)

EOS;

        return $this->connection->exec($sql);
    }

}