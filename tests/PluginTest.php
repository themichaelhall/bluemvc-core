<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\PluginInterface;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestPlugins\SetContentTestPlugin;
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
        $response = new BasicTestResponse();
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
            [[new SetContentTestPlugin(false, false)], 'Hello World!onPostRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(false, true)], 'Hello World!onPostRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(false, false)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(false, true)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(false, false)], 'Hello World!', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(false, true)], 'Hello World!', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(false, false)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(false, true)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(false, false)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(false, true)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            [[new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(false, false)], 'onPreRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(false, true)], 'onPreRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(false, false)], 'onPreRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(false, true)], 'onPreRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            [[new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
        ];
    }

    /**
     * Test getPlugins method for application with no plugins.
     */
    public function testGetPluginsForApplicationWithNoPlugins()
    {
        self::assertSame([], $this->myApplication->getPlugins());
    }

    /**
     * Test getPlugins method for application with plugins.
     */
    public function testGetPluginsForApplicationWithPlugins()
    {
        $setHeaderTestPlugin = new SetHeaderTestPlugin(false, false);
        $setContentTestPlugin = new SetContentTestPlugin(false, false);

        $this->myApplication->addPlugin($setHeaderTestPlugin);
        $this->myApplication->addPlugin($setContentTestPlugin);

        self::assertSame([$setHeaderTestPlugin, $setContentTestPlugin], $this->myApplication->getPlugins());
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
