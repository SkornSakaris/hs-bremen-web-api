<?php

namespace HsBremen\WebApi\Entity;

use Swagger\Annotations as SWG;

/**
 * Class Module
 *
 * @package HsBremen\WebApi\Entity
 * @SWG\Definition(
 *     definition="module",
 *     type="object"
 * )
 */
class Module implements \JsonSerializable
{
    /**
     * @var int $id
     * @SWG\Property(type="integer", format="int32", description="Die interne Id des Moduls")
     */
    private $id;

    /**
     * @var boolean $generated
     * @SWG\Property(type="boolean", description="Ob das Modul vom Benutzer erstellt wurde")
     */
    private $generated;

    /**
     * @var string $code
     * @SWG\Property(type="string", description="Der Code das Moduls (z.B. PO)")
     */
    private $code;

    /**
     * @var string $shortname
     * @SWG\Property(type="string")
     */
    private $shortname;

    /**
     * @var string $longname
     * @SWG\Property(type="string")
     */
    private $longname;

    /**
     * @var string $description
     * @SWG\Property(type="string")
     */
    private $description;

    /**
     * @var int $semester
     * @SWG\Property(type="integer", format="int32")
     */
    private $semester;

    /**
     * @var int $ects
     * @SWG\Property(type="integer", format="int32")
     */
    private $ects;

    /**
     * @var string $conditions
     * @SWG\Property(type="string")
     */
    private $conditions;

    /**
     * @var string $lecturer
     * @SWG\Property(type="string")
     */
    private $lecturer;

    /**
     * @var int $attempt
     * @SWG\Property(type="integer", format="int32")
     */
    private $attempt;

    /**
     * @var float $grade
     * @SWG\Property(type="number", format="float")
     */
    private $grade;

    /**
     * Module constructor.
     *
     * @param array $params
     */
    public function __construct($params = array()) {
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @param array $row
     * @return Module
     */
    public static function createFromArray(array $row){
        $module = new self();

        if (array_key_exists('id', $row)) {
            $module->setId($row['id']);
        }
        if (array_key_exists('generated', $row)) {
            $module->setGenerated($row['generated']);
        }
        if (array_key_exists('code', $row)) {
            $module->setCode($row['code']);
        }
        if (array_key_exists('shortname', $row)) {
            $module->setShortname($row['shortname']);
        }
        if (array_key_exists('longname', $row)) {
            $module->setLongname($row['longname']);
        }
        if (array_key_exists('description', $row)) {
            $module->setDescription($row['description']);
        }
        if (array_key_exists('semester', $row)) {
            $module->setSemester($row['semester']);
        }
        if (array_key_exists('ects', $row)) {
            $module->setEcts($row['ects']);
        }
        if (array_key_exists('conditions', $row)) {
            $module->setConditions($row['conditions']);
        }
        if (array_key_exists('lecturer', $row)) {
            $module->setLecturer($row['lecturer']);
        }
        if (array_key_exists('attempt', $row)) {
            $module->setAttempt($row['attempt']);
        }
        if (array_key_exists('grade', $row)) {
            $module->setGrade($row['grade']);
        }

        return $module;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'generated' => $this->generated,
            'code' => $this->code,
            'shortname' => $this->shortname,
            'longname' => $this->longname,
            'description' => $this->description,
            'semester' => $this->semester,
            'ects' => $this->ects,
            'conditions' => $this->conditions,
            'lecturer' => $this->lecturer,
            'attempt' => $this->attempt,
            'grade' => $this->grade,
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getGenerated()
    {
        return $this->generated;
    }

    /**
     * @param mixed $generated
     */
    public function setGenerated($generated)
    {
        $this->generated = $generated;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * @param mixed $shortname
     */
    public function setShortname($shortname)
    {
        $this->shortname = $shortname;
    }

    /**
     * @return mixed
     */
    public function getLongname()
    {
        return $this->longname;
    }

    /**
     * @param mixed $longname
     */
    public function setLongname($longname)
    {
        $this->longname = $longname;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * @param mixed $semester
     */
    public function setSemester($semester)
    {
        $this->semester = $semester;
    }

    /**
     * @return mixed
     */
    public function getEcts()
    {
        return $this->ects;
    }

    /**
     * @param mixed $ects
     */
    public function setEcts($ects)
    {
        $this->ects = $ects;
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param mixed $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return mixed
     */
    public function getLecturer()
    {
        return $this->lecturer;
    }

    /**
     * @param mixed $lecturer
     */
    public function setLecturer($lecturer)
    {
        $this->lecturer = $lecturer;
    }

    /**
     * @return mixed
     */
    public function getAttempt()
    {
        return $this->attempt;
    }

    /**
     * @param mixed $attempt
     */
    public function setAttempt($attempt)
    {
        $this->attempt = $attempt;
    }

    /**
     * @return mixed
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param mixed $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }




}