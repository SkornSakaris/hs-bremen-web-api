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
        $sql   = <<<EOS
SELECT m.*
FROM `moduls` m
WHERE m.id = :id
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
            ->with($sql, ['id' => 1])
            ->willReturn($moduls)
        ;

        self::assertEquals($module, $this->repository->getById(1));
    }

    /**
     * @test
     */
    public function getAllModules()
    {
        $sql   = <<<EOS
SELECT m.*
FROM `moduls` m
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
            ->with($sql)
            ->willReturn($moduls)
        ;

        $result = $this->repository->getAll();

        self::assertEquals($module, $result[0]);
    }

    /**
     * @test
     */
    public function insertAnModule()
    {
        $moduleData = [
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
        ];

        $this->db->expects(self::once())
            ->method('insert')
            ->with('`moduls`', $moduleData)
        ;

        $this->db->expects(self::once())
            ->method('lastInsertId')
            ->willReturn(1)
        ;

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

        $this->repository->save($module);

        self::assertEquals(1, $module->getId());
    }
}