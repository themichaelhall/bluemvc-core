<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestClasses;

use Stringable;

/**
 * A stringable test class.
 */
class StringableTestClass implements Stringable
{
    /**
     * StringableTestClass constructor.
     *
     * @param string $text The text.
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Returns the content as a string.
     *
     * @return string The content as a string.
     */
    public function __toString(): string
    {
        return 'Text is "' . $this->text . '"';
    }

    /**
     * @var string The text.
     */
    private string $text;
}
