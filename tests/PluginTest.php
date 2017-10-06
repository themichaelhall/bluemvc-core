<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\PluginInterface;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestPlugins\SetHeaderTestPlugin;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\FilePath;
use DataTypes\Url;

/**
 * Test plugin.
 */
class PluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test plugin handling.
     *
     * @dataProvider pluginHandlingDataProvider
     *
     * @param PluginInterface[] $plugins            The plugins.
     * @param string            $expectedContent    The expected content.
     * @param string[]          $expectedHeaders    The expected headers.
     * @param int               $expectedStatusCode The expected status code.
     */
    public function testPluginHandling(array $plugins, $expectedContent, array $expectedHeaders, $expectedStatusCode)
    {
        foreach ($plugins as $plugin) {
            $this->myApplication->addPlugin($plugin);
        }

        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $this->myApplication->run($request, $response);

        self::assertSame($expectedContent, $response->getContent());
        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for plugin handling tests.
     *
     * @return array The data.
     */
    public function pluginHandlingDataProvider()
    {
        return [
            [[], 'Hello World!', [], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, false)], 'Hello World!', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, true)], 'Hello World!', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
        ];
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication = new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS));
        $this->myApplication->addRoute(new Route('', BasicTestController::class));
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->myApplication = null;
    }

    /**
     * @var ApplicationInterface My application.
     */
    private $myApplication;
}
