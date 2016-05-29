<?php

namespace HsBremen\WebApi\Tests\Module;

use HsBremen\WebApi\Entity\Module;
use HsBremen\WebApi\Module\ModuleRepository;

class ModuleRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Doctrine\DBAL\Connection|\PHPUnit_Framework_MockObject_MockObject $db */
    private $db;

    /** @var  ModuleRepository */
    private $repository;

    public function setUp()
    {
        $this->db = self::getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->repository = new ModuleRepository($this->db);
//        $this->repository = self::getMockBuilder(ModuleRepository::class)
//            ->disableOriginalConstructor()
//            ->getMock()
//        ;
    }

    /**
     * @test
     */
    public function shouldReturnTableName()
    {
        self::assertEquals('moduls', $this->repository->getTableName());
    }

    public function shouldCreateTable()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `moduls` (
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
        $this->db->expects(self::once())
            ->method('exec')
            ->with($sql)
        ;

        $this->repository->createTable();
    }


    /**
     * @test
     */
    public function getModuleById()
    {
        $sql = <<<EOS
SELECT m.*, um.lecturer, um.attempt, um.grade
FROM `users_moduls` um
INNER JOIN `moduls` m
ON um.module_id = m.id
WHERE um.user_id = :user_id
AND um.module_id = :module_id
ORDER BY m.semester ASC
EOS;
        $module = new Module(
            [
                'id' => 1,
                'generated' => true,
                'code' => '0.0',
                'shortname' => 'TEST1',
                'longname' => 'Test-Modul-1',
                'description' => 'Test-Modul-1-Beschreibung',
                'semester' => 1,
                'ects' => 3,
                'conditions' => '-',
                'lecturer' => null,
                'attempt' => null,
                'grade' => null,
            ]
        );

        $moduls = [
            [
                'id' => 1,
                'generated' => true,
                'code' => '0.0',
                'shortname' => 'TEST1',
                'longname' => 'Test-Modul-1',
                'description' => 'Test-Modul-1-Beschreibung',
                'semester' => 1,
                'ects' => 3,
                'conditions' => '-',
                'lecturer' => null,
                'attempt' => null,
                'grade' => null,
            ],
        ];

        $this->db->expects(self::once())
            ->method('fetchAll')
            ->with($sql, ['user_id' => 1, 'module_id' => 1])
            ->willReturn($moduls)
        ;

        self::assertEquals($module, $this->repository->getModuleById(1, 1));
    }

    /**
     * @test
     */
    public function getAllModules()
    {
        $sql = <<<EOS
SELECT m.*, um.lecturer, um.attempt, um.grade
FROM `users_moduls` um
INNER JOIN `moduls` m
ON um.module_id = m.id
WHERE um.user_id = :user_id
ORDER BY m.semester ASC
EOS;
        $module = new Module(
            [
                'id' => 1,
                'generated' => true,
                'code' => '0.0',
                'shortname' => 'TEST1',
                'longname' => 'Test-Modul-1',
                'description' => 'Test-Modul-1-Beschreibung',
                'semester' => 1,
                'ects' => 3,
                'conditions' => '-',
                'lecturer' => null,
                'attempt' => null,
                'grade' => null,
            ]
        );

        $moduls = [
            [
                'id' => 1,
                'generated' => true,
                'code' => '0.0',
                'shortname' => 'TEST1',
                'longname' => 'Test-Modul-1',
                'description' => 'Test-Modul-1-Beschreibung',
                'semester' => 1,
                'ects' => 3,
                'conditions' => '-',
                'lecturer' => null,
                'attempt' => null,
                'grade' => null,
            ],
        ];

        $this->db->expects(self::once())
            ->method('fetchAll')
            ->with($sql, ['user_id' => 1])
            ->willReturn($moduls)
        ;

        $result = $this->repository->getAllModuls(1);

        self::assertEquals($module, $result[0]);
    }

    /**
     * @test
     */
    public function insertModuleAndReturn()
    {
        $userId = 1;

        $module = new Module(
            [
                'generated' => 'true',
                'code' => '0.0',
                'shortname' => 'MUSTER',
                'longname' => 'Muster-Modul',
                'description' => 'Muster-Beschreibung',
                'semester' => 1,
                'ects' => 5,
                'conditions' => '-',
                'lecturer' => 'Mustermann',
                'attempt' => 1,
                'grade' => 0.0,
            ]
        );

        $moduleData = [
            'generated' => 'true',
            'code' => '0.0',
            'shortname' => 'MUSTER',
            'longname' => 'Muster-Modul',
            'description' => 'Muster-Beschreibung',
            'semester' => 1,
            'ects' => 5,
            'conditions' => '-',
            'lecturer' => 'Mustermann',
            'attempt' => 1,
            'grade' => 0.0,
        ];

        $this->db->expects(self::once())
            ->method('lastInsertId')
            ->willReturn(1)
        ;

        $result = $this->repository->insertModuleAndReturn($userId, $module);

        self::assertEquals(1, $result->getId());
    }

    /**
     * @test
     */
    public function insertModule()
    {
        $moduleRawData = [
            'generated' => 'true',
            'code' => '0.0',
            'shortname' => 'MUSTER',
            'longname' => 'Muster-Modul',
            'description' => 'Muster-Beschreibung',
            'semester' => 1,
            'ects' => 5,
            'conditions' => '-',
            'lecturer' => 'Mustermann',
            'attempt' => 1,
            'grade' => 0.0,
        ];

        $moduleData = [
            'generated' => true,
            'code' => '0.0',
            'shortname' => 'MUSTER',
            'longname' => 'Muster-Modul',
            'description' => 'Muster-Beschreibung',
            'semester' => 1,
            'ects' => 5,
            'conditions' => '-',
        ];

        $this->db->expects(self::once())
            ->method('insert')
            ->with('`moduls`', $moduleData)
        ;

        $this->repository->insertModule($moduleRawData);
    }

    /**
     * @test
     */
    public function insertUserModuleRelation()
    {
        $userId = 1;

        $moduleRawData = [
            'id' => 123,
            'generated' => 'true',
            'code' => '0.0',
            'shortname' => 'MUSTER',
            'longname' => 'Muster-Modul',
            'description' => 'Muster-Beschreibung',
            'semester' => 1,
            'ects' => 5,
            'conditions' => '-',
            'lecturer' => 'Mustermann',
            'attempt' => 1,
            'grade' => 0.0,
        ];

        $moduleData = [
            'user_id' => 1,
            'module_id' => 123,
            'lecturer' => 'Mustermann',
            'attempt' => 1,
            'grade' => 0.0,
        ];

        $this->db->expects(self::once())
            ->method('insert')
            ->with('`users_moduls`', $moduleData)
        ;

        $this->repository->insertUserModuleRelation($userId, $moduleRawData);
    }
}
