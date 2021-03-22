<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\PluginInterface;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use BlueMvc\Core\Tests\Helpers\TestPlugins\SetContentTestPlugin;
use BlueMvc\Core\Tests\Helpers\TestPlugins\SetHeaderTestPlugin;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\FilePath;
use DataTypes\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test plugin.
 */
class PluginTest extends TestCase
{
    /**
     * Test plugin handling.
     *
     * @dataProvider pluginHandlingDataProvider
     *
     * @param string            $url                The relative url.
     * @param PluginInterface[] $plugins            The plugins.
     * @param string            $expectedContent    The expected content.
     * @param string[]          $expectedHeaders    The expected headers.
     * @param int               $expectedStatusCode The expected status code.
     */
    public function testPluginHandling(string $url, array $plugins, string $expectedContent, array $expectedHeaders, int $expectedStatusCode)
    {
        foreach ($plugins as $plugin) {
            $this->application->addPlugin($plugin);
        }

        $request = new BasicTestRequest(Url::parse('http://localhost/' . $url), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertSame($expectedContent, self::normalizeEndOfLine($response->getContent()));
        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for plugin handling tests.
     *
     * @return array The data.
     */
    public function pluginHandlingDataProvider(): array
    {
        return [
            ['', [], 'Hello World!', [], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, false)], 'Hello World!', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, true)], 'Hello World!', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, false)], 'Hello World!onPostRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, true)], 'Hello World!onPostRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(false, false)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(false, true)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(false, false)], 'Hello World!', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(false, true)], 'Hello World!', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(false, false)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(false, true)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(false, false)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(false, true)], 'Hello World!onPostRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(false, false)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(false, true)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(false, false)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(false, true)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\n", ['X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, false)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\n", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, true)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\n", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(false, false)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\nonPostRequest", ['X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetContentTestPlugin(false, true)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\nonPostRequest", ['X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetContentTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(false, false)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\nonPostRequest", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(false, true)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\nonPostRequest", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(false, false)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\n", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(false, true)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\n", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(false, false)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\nonPostRequest", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(false, true)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\nonPostRequest", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1', 'X-PluginOnPostRequest' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(false, false)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\nonPostRequest", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(false, true)], "<html><body><h1>Request Failed: Error: 404</h1></body></html>\nonPostRequest", ['X-PluginOnPreRequest' => '1', 'X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], StatusCode::NOT_FOUND],
            ['actionResult/notfound', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(false, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(false, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(false, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(false, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/notfound', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [], '', [], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, false)], '', ['X-PluginOnPostRequest' => '1'], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, true)], '', ['X-PluginOnPostRequest' => '1'], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, false)], 'onPostRequest', [], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, true)], 'onPostRequest', [], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(false, false)], 'onPostRequest', ['X-PluginOnPostRequest' => '1'], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(false, true)], 'onPostRequest', ['X-PluginOnPostRequest' => '1'], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, false), new SetContentTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPostRequest' => '1'], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPostRequest' => '1'], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(false, true), new SetContentTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, false), new SetContentTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(false, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(false, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(true, false)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetHeaderTestPlugin(true, true), new SetContentTestPlugin(true, true)], '', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(false, false)], 'onPostRequest', ['X-PluginOnPostRequest' => '1'], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(false, true)], 'onPostRequest', ['X-PluginOnPostRequest' => '1'], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, false), new SetHeaderTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(false, false)], 'onPostRequest', [], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(false, true)], 'onPostRequest', [], StatusCode::INTERNAL_SERVER_ERROR],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(true, false)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(false, true), new SetHeaderTestPlugin(true, true)], 'onPreRequest', ['X-PluginOnPreRequest' => '1'], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(false, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(false, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, false), new SetHeaderTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(false, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(false, true)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(true, false)], 'onPreRequest', [], StatusCode::OK],
            ['actionResult/forbidden', [new SetContentTestPlugin(true, true), new SetHeaderTestPlugin(true, true)], 'onPreRequest', [], StatusCode::OK],
        ];
    }

    /**
     * Test getPlugins method for application with no plugins.
     */
    public function testGetPluginsForApplicationWithNoPlugins()
    {
        self::assertSame([], $this->application->getPlugins());
    }

    /**
     * Test getPlugins method for application with plugins.
     */
    public function testGetPluginsForApplicationWithPlugins()
    {
        $setHeaderTestPlugin = new SetHeaderTestPlugin(false, false);
        $setContentTestPlugin = new SetContentTestPlugin(false, false);

        $this->application->addPlugin($setHeaderTestPlugin);
        $this->application->addPlugin($setContentTestPlugin);

        self::assertSame([$setHeaderTestPlugin, $setContentTestPlugin], $this->application->getPlugins());
    }

    /**
     * Set up.
     */
    public function setUp(): void
    {
        parent::setUp();

        $DS = DIRECTORY_SEPARATOR;

        $this->application = new BasicTestApplication(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $this->application->addViewRenderer(new BasicTestViewRenderer());
        $this->application->addRoute(new Route('', BasicTestController::class));
        $this->application->addRoute(new Route('actionResult', ActionResultTestController::class));
        $this->application->setErrorControllerClass(ErrorTestController::class);
    }

    /**
     * Tear down.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->application = null;
    }

    /**
     * Normalizes the end of line character(s) to \n, so tests will pass even if the newline(s) in tests files are converted, e.g. by Git.
     *
     * @param string $s
     *
     * @return string
     */
    private static function normalizeEndOfLine(string $s): string
    {
        return str_replace("\r\n", "\n", $s);
    }

    /**
     * @var ApplicationInterface My application.
     */
    private $application;
}
