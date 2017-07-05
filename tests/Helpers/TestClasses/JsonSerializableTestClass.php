<?php

namespace BlueMvc\Core\Tests\Helpers\TestClasses;

/**
 * A basic test class that implements the JsonSerializable interface.
 */
class JsonSerializableTestClass implements \JsonSerializable
{
    /**
     * JsonSerializableTestClass constructor.
     *
     * @param int    $intVal    The integer value.
     * @param string $stringVal The string value.
     */
    public function __construct($intVal, $stringVal)
    {
        $this->intVal = $intVal;
        $this->stringVal = $stringVal;
    }

    /**
     * Returns the content to json-serialize.
     *
     * @return mixed The content to json-serialize
     */
    public function jsonSerialize()
    {
        return ['text' => $this->stringVal];
    }

    /**
     * @var int My integer value.
     */
    public $intVal;

    /**
     * @var string My string value.
     */
    public $stringVal;
}
