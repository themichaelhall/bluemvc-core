<?php

namespace BlueMvc\Core\Tests\Helpers\TestClasses;

/**
 * A stringable test class.
 */
class StringableTestClass
{
    /**
     * StringableTestClass constructor.
     *
     * @param string $text The text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Returns the content as a string.
     *
     * @return string The content as a string.
     */
    public function __toString()
    {
        return 'Text is "' . $this->text . '"';
    }

    /**
     * @var string My text.
     */
    private $text;
}
