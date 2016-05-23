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
class Module
{
    /**
     * @var int $id
     * @SWG\Property(type="integer", format="int32")
     */
    private $id;
    private $generated;
    private $code;
    private $shortname;
    private $longname;
    private $description;
    private $ects;
    private $conditions;
    private $lecturer;
    private $attempt;
    private $grade;

//    /**
//     * Module constructor.
//     *
//     * @param $id
//     * @param $generated
//     * @param $code
//     * @param $shortname
//     * @param $longname
//     * @param $description
//     * @param $ects
//     * @param $conditions
//     * @param $lecturer
//     * @param $attempt
//     * @param $grade
//     */
//    public function __construct($id, $generated, $code, $shortname, $longname, $description, $ects, $conditions, $lecturer, $attempt, $grade)
//    {
//        $this->id = $id;
//        $this->generated = $generated;
//        $this->code = $code;
//        $this->shortname = $shortname;
//        $this->longname = $longname;
//        $this->description = $description;
//        $this->ects = $ects;
//        $this->conditions = $conditions;
//        $this->lecturer = $lecturer;
//        $this->attempt = $attempt;
//        $this->grade = $grade;
//    }
    public function __construct($params = array()) {
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }
    }

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