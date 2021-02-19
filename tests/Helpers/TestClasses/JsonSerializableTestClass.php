<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestClasses;

use JsonSerializable;

/**
 * A basic test class that implements the JsonSerializable interface.
 */
class JsonSerializableTestClass implements JsonSerializable
{
    /**
     * JsonSerializableTestClass constructor.
     *
     * @param int    $intVal    The integer value.
     * @param string $stringVal The string value.
     */
    public function __construct(int $intVal, string $stringVal)
    {
        $this->intVal = $intVal;
        $this->stringVal = $stringVal;
    }

    /**
     * Returns the content to json-serialize.
     *
     * @return mixed The content to json-serialize
     */
    public function jsonSerialize(): array
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
