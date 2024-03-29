<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\DefaultRoute;
use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use DataTypes\Net\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test DefaultRoute class.
 */
class DefaultRouteTest extends TestCase
{
    /**
     * Test getControllerClassName method.
     */
    public function testGetControllerClassName()
    {
        $route = new DefaultRoute(BasicTestController::class);

        self::assertSame(BasicTestController::class, $route->getControllerClassName());
    }

    /**
     * Test that setting an undefined controller class name throws exception.
     */
    public function testSetUndefinedControllerClassThrowsException()
    {
        self::expectException(InvalidControllerClassException::class);
        self::expectExceptionMessage('"BlueMvc\Core\FooBar" is not a valid controller class.');

        new DefaultRoute('BlueMvc\\Core\\FooBar');
    }

    /**
     * Test that setting an invalid controller class name throws exception.
     */
    public function testSetInvalidControllerClassThrowsException()
    {
        self::expectException(InvalidControllerClassException::class);
        self::expectExceptionMessage('"BlueMvc\Core\Request" is not a valid controller class.');

        new DefaultRoute('BlueMvc\\Core\\Request');
    }

    /**
     * @dataProvider matchesDataProvider
     *
     * Test matches method.
     *
     * @param string $urlPath            The url path.
     * @param string $expectedAction     The expected action.
     * @param array  $expectedParameters The expected parameters.
     */
    public function testMatches(string $urlPath, string $expectedAction, array $expectedParameters)
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://example.com' . $urlPath), new Method('GET')));

        self::assertSame($expectedAction, $routeMatch->getAction());
        self::assertSame($expectedParameters, $routeMatch->getParameters());
    }

    /**
     * Data provider for matches test.
     *
     * @return array The data.
     */
    public function matchesDataProvider(): array
    {
        return [
            ['/', '', []],
            ['/foo', 'foo', []],
            ['/foo/', 'foo', ['']],
            ['/foo/bar', 'foo', ['bar']],
            ['/foo/bar/', 'foo', ['bar', '']],
            ['/foo/bar/baz', 'foo', ['bar', 'baz']],
            ['/foo/bar/baz/', 'foo', ['bar', 'baz', '']],
        ];
    }
}
