<?php

namespace BlueMvc\Core\Tests\Helpers\TestClasses;

/**
 * A simple test class.
 */
class SimpleTestClass
{
    /**
     * SimpleTestClass constructor.
     *
     * @param string $text The text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @var string My text.
     */
    private $text;
}
