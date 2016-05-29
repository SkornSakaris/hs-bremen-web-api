<?php

namespace HsBremen\WebApi\Module;

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Database\DatabaseException;
use HsBremen\WebApi\Entity\Module;
use Silex\Application;

class ModuleRepository
{
    /**
     * @var Connection $connection
     */
    private $connection;

    /**
     * @var string $tableName
     */
    private $tableName;

    /**
     * ModuleRepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->tableName = 'moduls';
    }

    public function dropTable()
    {
        $sql = "DROP TABLE IF EXISTS `{$this->getTableName()}`";
        $this->connection->exec($sql);
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
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '1.1', 'MATHE1', 'Lineare Algebra', 'Keine Beschreibung', 1, 6, '-')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '1.3', 'GELEK1', 'Grundlagen der Elektrotechnik 1', 'Keine Beschreibung', 1, 6, '-')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '1.4', 'INFO', 'Informatik', 'Keine Beschreibung', 1, 6, '-')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '1.5', 'PROG1', 'Programmieren 1', 'Keine Beschreibung', 1, 6, '-')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '1.6', 'ENGL', 'Englisch', 'Keine Beschreibung', 1, 6, '-')";

        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '1.2', 'MATHE2', 'Analysis', 'Keine Beschreibung', 2, 6, '-')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '2.1', 'GELEK2', 'Grundlagen der Elektrotechnik 2', 'Keine Beschreibung', 2, 6, '1.3')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '2.2', 'DIGIT', 'Entwurf digitaler Schaltungen', 'Keine Beschreibung', 2, 6, '-')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '2.3', 'PROG2', 'Programmieren 2', 'Keine Beschreibung', 2, 6, '1.5')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '2.4', 'PHYSIK', 'Physik', 'Keine Beschreibung', 2, 6, '-')";

        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '3.1', 'MATHE3', 'Höhere Ingenierusmathematik', 'Keine Beschreibung', 3, 6, '1.1, 1.2')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '3.4', 'REDIG', 'Rechnergestützter Schaltungsentwurf', 'Keine Beschreibung', 3, 6, '2.2')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '3.5', 'BESYST', 'Betriebssysteme', 'Keine Beschreibung', 3, 6, '1.5')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '3.6', 'SOFTW1', 'Softwaretechnik 1', 'Keine Beschreibung', 3, 6, '1.4')";
        $sql[] = "INSERT INTO `{$this->getTableName()}` VALUES(null, true, '3.7', 'ELMESS', 'Grundlagen der Elektrische Messtechnik', 'Keine Beschreibung', 3, 6, '2.1')";

        foreach ($sql as $query)
        {
            $this->connection->exec($query);
        }
    }

    public function getAllModuls($userId)
    {
        $sql = <<<EOS
SELECT m.*, um.lecturer, um.attempt, um.grade
FROM `users_moduls` um
INNER JOIN `{$this->getTableName()}` m
ON um.module_id = m.id
WHERE um.user_id = :user_id
ORDER BY m.semester ASC
EOS;
        $moduls = $this->connection->fetchAll($sql, ['user_id' => $userId]);

        $result = [];
        foreach ($moduls as $row) {
            $result[] = Module::createFromArray($row);
        }

        return $result;
    }

    public function getModuleById($userId, $moduleId)
    {
        $sql = <<<EOS
SELECT m.*, um.lecturer, um.attempt, um.grade
FROM `users_moduls` um
INNER JOIN `{$this->getTableName()}` m
ON um.module_id = m.id
WHERE um.user_id = :user_id
AND um.module_id = :module_id
ORDER BY m.semester ASC
EOS;
        $moduls = $this->connection->fetchAll($sql, ['user_id' => $userId, 'module_id' => $moduleId]);
        if (count($moduls) === 0) {
            throw new DatabaseException(
                sprintf('Module with id "%d" not exists!', $moduleId)
            );
        }

        return Module::createFromArray($moduls[0]);
    }

    /**
     * Erstellt ein neues Modul, verknüpftes mit dem Benutzer und gibt es um die erstellte Id erweitert zurück
     *
     * @param int $userId
     * @param array $moduleData
     *
     * @return Module $module
     */
    public function insertModuleAndReturn($userId, $moduleData)
    {
        //$data = $module->jsonSerialize();

        $this->insertModule($moduleData);

        $moduleId = $this->connection->lastInsertId();
        $moduleData['id'] = $moduleId;

        $this->insertUserModuleRelation($userId, $moduleData);

        return new Module($moduleData);
    }

    /**
     * @param array $moduleData
     */
    public function insertModule($moduleData)
    {
        unset($moduleData['id']);
        unset($moduleData['lecturer']);
        unset($moduleData['attempt']);
        unset($moduleData['grade']);

        if ($moduleData['generated'] === 'true')
        {
            $moduleData['generated'] = true;
        }
        else
        {
            $moduleData['generated'] = false;
        }

        $this->connection->insert("`{$this->getTableName()}`", $moduleData);
    }

    /**
     * @param int $userId
     * @param array $moduleData
     */
    public function insertUserModuleRelation($userId, $moduleData)
    {
        $relationData = [
            'user_id' => $userId,
            'module_id' => $moduleData['id'],
            'lecturer' => $moduleData['lecturer'],
            'attempt' => $moduleData['attempt'],
            'grade' => $moduleData['grade'],
        ];

        $this->connection->insert("`users_moduls`", $relationData);
    }

    /**
     * Aktualisiert das Module anhand der Id des Moduls und gibt das aktualisierte Modul zurück
     *
     * @param int $userId
     * @param Module $module
     *
     * @return Module $module
     *
     * @throws DatabaseException
     */
    public function updateModuleByIdAndReturn($userId, $module)
    {
        $data = $module->jsonSerialize();

        $this->updateModule($data);

        $this->updateUserModuleRelation($userId, $data);

        return $module;
    }

    /**
     * @param array $moduleData
     */
    public function updateModule($moduleData)
    {
        $moduleId = $moduleData['id'];

        unset($moduleData['id']);
        unset($moduleData['lecturer']);
        unset($moduleData['attempt']);
        unset($moduleData['grade']);

        $this->connection->update("`{$this->getTableName()}`", $moduleData, ['id' => $moduleId]);
    }

    /**
     * @param int $userId
     * @param array $moduleData
     */
    public function updateUserModuleRelation($userId, $moduleData)
    {
        $moduleId = $moduleData['id'];

        $relationData = [
            'lecturer' => $moduleData['lecturer'],
            'attempt' => $moduleData['attempt'],
            'grade' => $moduleData['grade'],
        ];

        $this->connection->update("`users_moduls`", $relationData, ['user_id' => $userId, 'module_id' => $moduleId]);
    }

    /**
     * Löscht das Modul und die Benutzer-Modul-Verknüpfung anhand der übergebenen Id
     *
     * @param int $moduleId
     * @return array
     */
    public function deleteModuleById($moduleId)
    {
        $modulsDeleted = $this->deleteModule($moduleId);
        $relationsDeleted = $this->deleteUserModuleRelation($moduleId);

        $data = ['modulsDeleted' => $modulsDeleted, 'relationsDeleted' => $relationsDeleted];
        return $data;
    }

    /**
     * @param int $moduleId
     * @return int
     */
    public function deleteModule($moduleId)
    {
        return $this->connection->delete("`{$this->getTableName()}`", ['id' => $moduleId]);
    }

    /**
     * @param int $moduleId
     *
     * @return int
     */
    public function deleteUserModuleRelation($moduleId)
    {
        return $this->connection->delete("`users_moduls`", ['module_id' => $moduleId]);
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

}