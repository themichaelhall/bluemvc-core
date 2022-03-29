<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Exceptions\InvalidRoutePathException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use DataTypes\Net\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test Route class.
 */
class RouteTest extends TestCase
{
    /**
     * Test path with invalid character.
     */
    public function testPathWithInvalidCharacter()
    {
        self::expectException(InvalidRoutePathException::class);
        self::expectExceptionMessage('Path "Foo*Bar" contains invalid character "*".');

        new Route('Foo*Bar', BasicTestController::class);
    }

    /**
     * Test path with empty part.
     */
    public function testPathWithEmptyPart()
    {
        self::expectException(InvalidRoutePathException::class);
        self::expectExceptionMessage('Path "Foo//Bar" contains empty part.');

        new Route('Foo//Bar', BasicTestController::class);
    }

    /**
     * Test that setting an undefined controller class name throws exception.
     */
    public function testSetUndefinedControllerClassThrowsException()
    {
        self::expectException(InvalidControllerClassException::class);
        self::expectExceptionMessage('"BlueMvc\Core\FooBar" is not a valid controller class.');

        new Route('', 'BlueMvc\\Core\\FooBar');
    }

    /**
     * Test that setting an invalid controller class name throws exception.
     */
    public function testSetInvalidControllerClassThrowsException()
    {
        self::expectException(InvalidControllerClassException::class);
        self::expectExceptionMessage('"BlueMvc\Core\Request" is not a valid controller class.');

        new Route('', 'BlueMvc\\Core\\Request');
    }

    /**
     * Test getControllerClassName method.
     */
    public function testGetControllerClassName()
    {
        $route = new Route('', BasicTestController::class);

        self::assertSame(BasicTestController::class, $route->getControllerClassName());
    }

    /**
     * @dataProvider matchesDataProvider
     *
     * Test matches method.
     *
     * @param string      $path               The route path.
     * @param string      $urlPath            The url path.
     * @param bool        $expectedMatch      True if expected match, false otherwise.
     * @param string|null $expectedAction     The expected action or null if no match.
     * @param array|null  $expectedParameters The expected parameters or null if no match.
     */
    public function testMatches(string $path, string $urlPath, bool $expectedMatch, ?string $expectedAction = null, ?array $expectedParameters = null)
    {
        $route = new Route($path, BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://example.com' . $urlPath), new Method('GET')));

        self::assertSame($expectedMatch, $routeMatch !== null);
        self::assertSame($expectedAction, $routeMatch?->getAction());
        self::assertSame($expectedParameters, $routeMatch?->getParameters());
    }

    /**
     * Data provider for matches test.
     *
     * @return array The data.
     */
    public function matchesDataProvider(): array
    {
        return [
            ['', '/', true, '', []],
            ['', '/foo', true, 'foo', []],
            ['', '/foo/', false],
            ['', '/foo/bar', false],
            ['', '/foo/bar/', false],
            ['', '/foo/bar/baz', false],
            ['', '/foo/bar/baz/', false],
            ['foo', '/', false],
            ['foo', '/foo', false],
            ['foo', '/foo/', true, '', []],
            ['foo', '/foo/bar', true, 'bar', []],
            ['foo', '/foo/bar/', true, 'bar', ['']],
            ['foo', '/foo/bar/baz', true, 'bar', ['baz']],
            ['foo', '/foo/bar/baz/', true, 'bar', ['baz', '']],
            ['bar', '/', false],
            ['bar', '/foo', false],
            ['bar', '/foo/', false],
            ['bar', '/foo/bar', false],
            ['bar', '/foo/bar/', false],
            ['bar', '/foo/bar/baz', false],
            ['bar', '/foo/bar/baz/', false],
            ['foo/bar', '/', false],
            ['foo/bar', '/foo', false],
            ['foo/bar', '/foo/', false],
            ['foo/bar', '/foo/bar', false],
            ['foo/bar', '/foo/bar/', true, '', []],
            ['foo/bar', '/foo/bar/baz', true, 'baz', []],
            ['foo/bar', '/foo/bar/baz/', true, 'baz', ['']],
            ['foo/bar', '/foo/bar/baz/1', true, 'baz', ['1']],
            ['foo/bar', '/foo/bar/baz/1/', true, 'baz', ['1', '']],
            ['foo/bar', '/foo/bar/baz/1/2', true, 'baz', ['1', '2']],
            ['foo/baz', '/', false],
            ['foo/baz', '/foo', false],
            ['foo/baz', '/foo/', false],
            ['foo/baz', '/foo/bar', false],
            ['foo/baz', '/foo/bar/', false],
            ['foo/baz', '/foo/bar/baz', false],
            ['foo/baz', '/foo/bar/baz/', false],
            ['foo/baz', '/foo/bar/baz/1', false],
            ['foo/baz', '/foo/bar/baz/1/', false],
            ['foo/baz', '/foo/bar/baz/1/2', false],
            ['foo/bar/baz', '/', false],
            ['foo/bar/baz', '/foo', false],
            ['foo/bar/baz', '/foo/', false],
            ['foo/bar/baz', '/foo/bar', false],
            ['foo/bar/baz', '/foo/bar/', false],
            ['foo/bar/baz', '/foo/bar/baz', false],
            ['foo/bar/baz', '/foo/bar/baz/', true, '', []],
            ['foo/bar/baz', '/foo/bar/baz/1', true, '1', []],
            ['foo/bar/baz', '/foo/bar/baz/1/', true, '1', ['']],
            ['foo/bar/baz', '/foo/bar/baz/1/2', true, '1', ['2']],
        ];
    }
}
