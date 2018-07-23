<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Exceptions\ServerEnvironmentException;
use BlueMvc\Core\Request;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeFileGetContentsPhpInput;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeIsUploadedFile;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeSession;
use PHPUnit\Framework\TestCase;

/**
 * Test Request class.
 */
class RequestTest extends TestCase
{
    /**
     * Test getUrl method.
     */
    public function testGetUrl()
    {
        $request = new Request();

        self::assertSame('http://example.com/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method for https request.
     */
    public function testGetUrlForHttpsRequest()
    {
        $_SERVER['HTTPS'] = 'On';

        $request = new Request();

        self::assertSame('https://example.com/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with port.
     */
    public function testGetUrlWithPort()
    {
        $_SERVER['HTTP_HOST'] = 'example.com:8080';

        $request = new Request();

        self::assertSame('http://example.com:8080/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with empty query string.
     */
    public function testGetUrlWithEmptyQueryString()
    {
        $_SERVER['REQUEST_URI'] = '/foo/bar?';

        $request = new Request();

        self::assertSame('http://example.com/foo/bar?', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with query string.
     */
    public function testGetUrlWithQueryString()
    {
        $_SERVER['REQUEST_URI'] = '/foo/bar?baz=true';

        $request = new Request();

        self::assertSame('http://example.com/foo/bar?baz=true', $request->getUrl()->__toString());
    }

    /**
     * Test getMethod method.
     */
    public function testGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $request = new Request();

        self::assertSame('POST', $request->getMethod()->__toString());
    }

    /**
     * Test getHeaders method.
     */
    public function testGetHeaders()
    {
        $_SERVER['HTTP_HOST'] = 'www.example.com';
        $_SERVER['HTTP_ACCEPT_ENCODING'] = 'gzip, deflate';

        $request = new Request();

        self::assertSame(['Host' => 'www.example.com', 'Accept-Encoding' => 'gzip, deflate'], iterator_to_array($request->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $_SERVER['HTTP_ACCEPT_ENCODING'] = 'gzip, deflate';

        $request = new Request();

        self::assertSame('gzip, deflate', $request->getHeader('Accept-Encoding'));
        self::assertNull($request->getHeader('Foo-Bar'));
    }

    /**
     * Test getUserAgent method without user agent set.
     */
    public function testGetUserAgentWithoutUserAgentSet()
    {
        $request = new Request();

        self::assertSame('', $request->getUserAgent());
    }

    /**
     * Test getUserAgent method with user agent set.
     */
    public function testGetUserAgentWithUserAgentSet()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36';

        $request = new Request();

        self::assertSame('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', $request->getUserAgent());
    }

    /**
     * Test getFormParameters method without form parameters set.
     */
    public function testGetFormParametersWithoutFormParametersSet()
    {
        $request = new Request();

        self::assertSame([], iterator_to_array($request->getFormParameters()));
    }

    /**
     * Test getFormParameters method with form parameters set.
     */
    public function testGetFormParametersWithFormParametersSet()
    {
        $_POST =
            [
                'Foo' => '1',
                'Bar' => ['2', '3'],
            ];

        $request = new Request();

        self::assertSame(['Foo' => '1', 'Bar' => '2'], iterator_to_array($request->getFormParameters()));
    }

    /**
     * Test getFormParameter method.
     */
    public function testGetFormParameter()
    {
        $_POST =
            [
                'Foo' => '1',
                'Bar' => ['2', '3'],
            ];

        $request = new Request();

        self::assertSame('1', $request->getFormParameter('Foo'));
        self::assertSame('2', $request->getFormParameter('Bar'));
        self::assertNull($request->getFormParameter('Baz'));
    }

    /**
     * Test getQueryParameters method without query parameters set.
     */
    public function testGetQueryParametersWithoutQueryParametersSet()
    {
        $request = new Request();

        self::assertSame([], iterator_to_array($request->getQueryParameters()));
    }

    /**
     * Test getQueryParameters method with query parameters set.
     */
    public function testGetQueryParametersWithQueryParametersSet()
    {
        $_GET =
            [
                'Foo' => '1',
                'Bar' => ['2', '3'],
            ];

        $request = new Request();

        self::assertSame(['Foo' => '1', 'Bar' => '2'], iterator_to_array($request->getQueryParameters()));
    }

    /**
     * Test getQueryParameter method.
     */
    public function testGetQueryParameter()
    {
        $_GET =
            [
                'Foo' => '1',
                'Bar' => ['2', '3'],
            ];

        $request = new Request();

        self::assertSame('1', $request->getQueryParameter('Foo'));
        self::assertSame('2', $request->getQueryParameter('Bar'));
        self::assertNull($request->getQueryParameter('Baz'));
    }

    /**
     * Test getQueryParameter method with numeric parameter.
     */
    public function testGetQueryParameterWithNumericParameters()
    {
        $_GET =
            [
                1 => 2,
            ];

        $request = new Request();

        self::assertSame('2', $request->getQueryParameter('1'));
    }

    /**
     * Test getFormParameter method with numeric parameter.
     */
    public function testGetFormParameterWithNumericParameters()
    {
        $_POST =
            [
                1 => 2,
            ];

        $request = new Request();

        self::assertSame('2', $request->getFormParameter('1'));
    }

    /**
     * Test getUploadedFiles method with no uploaded files set.
     */
    public function testGetUploadedFilesWithNoUploadedFiles()
    {
        $request = new Request();

        self::assertSame([], iterator_to_array($request->getUploadedFiles()));
    }

    /**
     * Test getUploadedFiles method with uploaded files set.
     */
    public function testGetUploadedFilesWithUploadedFiles()
    {
        $DS = DIRECTORY_SEPARATOR;

        $_FILES =
            [
                'foo' => [
                    'name'     => 'Documents/Foo.doc',
                    'type'     => 'application/msword',
                    'tmp_name' => '/tmp/foo123.doc',
                    'size'     => '56789',
                    'error'    => '0',
                ],
                12345 => [
                    'name'     => '..\\Bar.html',
                    'type'     => 'text/html; charset=utf-8',
                    'tmp_name' => '/tmp/bar456.htm',
                    'size'     => 42,
                    'error'    => 0,
                ],
            ];

        $request = new Request();

        $uploadedFiles = $request->getUploadedFiles();

        self::assertSame(2, count($uploadedFiles));
        self::assertSame($DS . 'tmp' . $DS . 'foo123.doc', $uploadedFiles->get('foo')->getPath()->__toString());
        self::assertSame('Documents/Foo.doc', $uploadedFiles->get('foo')->getOriginalName());
        self::assertSame(56789, $uploadedFiles->get('foo')->getSize());
        self::assertSame($DS . 'tmp' . $DS . 'bar456.htm', $uploadedFiles->get('12345')->getPath()->__toString());
        self::assertSame('..\\Bar.html', $uploadedFiles->get('12345')->getOriginalName());
        self::assertSame(42, $uploadedFiles->get('12345')->getSize());
    }

    /**
     * Test getUploadedFile method.
     */
    public function testGetUploadedFile()
    {
        $DS = DIRECTORY_SEPARATOR;

        $_FILES =
            [
                'foo' => [
                    'name'     => 'Documents/Foo.doc',
                    'type'     => 'application/msword',
                    'tmp_name' => '/tmp/foo123.doc',
                    'size'     => '56789',
                    'error'    => '0',
                ],
            ];

        $request = new Request();

        self::assertSame($DS . 'tmp' . $DS . 'foo123.doc', $request->getUploadedFile('foo')->getPath()->__toString());
        self::assertSame('Documents/Foo.doc', $request->getUploadedFile('foo')->getOriginalName());
        self::assertSame(56789, $request->getUploadedFile('foo')->getSize());
        self::assertNull($request->getUploadedFile('FOO'));
        self::assertNull($request->getUploadedFile('bar'));
    }

    /**
     * Test error in uploaded files.
     *
     * @dataProvider errorInUploadedFilesDataProvider
     *
     * @param int    $error                    The file upload error.
     * @param string $expectedExceptionMessage The expected exception message or null if no exception was thrown.
     */
    public function testErrorInUploadedFiles($error, $expectedExceptionMessage)
    {
        $request = null;
        $exceptionMessage = null;

        try {
            $_FILES =
                [
                    'foo' => [
                        'name'     => 'Foo.txt',
                        'type'     => 'text/plain',
                        'tmp_name' => '/tmp/foo.txt',
                        'size'     => '100',
                        'error'    => $error,
                    ],
                ];

            $request = new Request();
        } catch (ServerEnvironmentException $exception) {
            $exceptionMessage = $exception->getMessage();
        }

        self::assertTrue($request === null || $request->getUploadedFile('foo') === null);
        self::assertSame($expectedExceptionMessage, $exceptionMessage);
    }

    /**
     * Data provider for error in uploaded files test.
     *
     * @return array The data.
     */
    public function errorInUploadedFilesDataProvider()
    {
        return [
            [UPLOAD_ERR_INI_SIZE, null],
            [UPLOAD_ERR_FORM_SIZE, null],
            [UPLOAD_ERR_PARTIAL, null],
            [UPLOAD_ERR_NO_FILE, null],
            [UPLOAD_ERR_NO_TMP_DIR, 'File upload failed: Missing a temporary folder (UPLOAD_ERR_NO_TMP_DIR).'],
            [UPLOAD_ERR_CANT_WRITE, 'File upload failed: Failed to write file to disk (UPLOAD_ERR_CANT_WRITE).'],
            [UPLOAD_ERR_EXTENSION, 'File upload failed: File upload stopped by extension (UPLOAD_ERR_EXTENSION).'],
        ];
    }

    /**
     * Test getUploadedFile method with multiple files.
     */
    public function testGetUploadedFileWithMultipleFiles()
    {
        $DS = DIRECTORY_SEPARATOR;

        $_FILES =
            [
                'foo' => [
                    'name'     => ['Documents/Foo.doc', 'Documents/Bar.txt'],
                    'type'     => ['application/msword', 'text/plain'],
                    'tmp_name' => ['/tmp/foo123.doc', '/tmp/bar456.doc'],
                    'size'     => [56789, 1234],
                    'error'    => [0, 0],
                ],
            ];

        $request = new Request();

        self::assertSame($DS . 'tmp' . $DS . 'foo123.doc', $request->getUploadedFile('foo')->getPath()->__toString());
        self::assertSame('Documents/Foo.doc', $request->getUploadedFile('foo')->getOriginalName());
        self::assertSame(56789, $request->getUploadedFile('foo')->getSize());
        self::assertNull($request->getUploadedFile('FOO'));
        self::assertNull($request->getUploadedFile('bar'));
    }

    /**
     * Test getUploadedFile method with file that is not an uploaded file.
     */
    public function testGetUploadedFileWithNotUploadedFile()
    {
        $_FILES =
            [
                'foo' => [
                    'name'     => 'Documents/Foo.doc',
                    'type'     => 'application/msword',
                    'tmp_name' => '/tmp/foo123.not-uploaded',
                    'size'     => '56789',
                    'error'    => '0',
                ],
            ];

        $request = new Request();

        self::assertEmpty($request->getUploadedFiles());
        self::assertNull($request->getUploadedFile('foo'));
    }

    /**
     * Test getCookies method with no cookies set.
     */
    public function testGetCookiesWithNoCookiesSet()
    {
        $request = new Request();

        self::assertSame([], iterator_to_array($request->getCookies()));
    }

    /**
     * Test getCookies method with cookies set.
     */
    public function testGetCookiesWithCookiesSet()
    {
        $_COOKIE =
            [
                'Foo' => 'Bar',
                1     => 2,
            ];

        $request = new Request();
        $cookies = $request->getCookies();

        self::assertSame('Bar', $cookies->get('Foo')->getValue());
        self::assertSame('2', $cookies->get('1')->getValue());
    }

    /**
     * Test getCookie method.
     */
    public function testGetCookie()
    {
        $_COOKIE =
            [
                'Foo' => 'Bar',
                1     => 2,
            ];

        $request = new Request();

        self::assertSame('Bar', $request->getCookie('Foo')->getValue());
        self::assertSame('2', $request->getCookie('1')->getValue());
        self::assertNull($request->getCookie('foo'));
        self::assertNull($request->getCookie('baz'));
    }

    /**
     * Test getCookieValue method.
     */
    public function testGetCookieValue()
    {
        $_COOKIE =
            [
                'Foo' => 'Bar',
                1     => 2,
            ];

        $request = new Request();

        self::assertSame('Bar', $request->getCookieValue('Foo'));
        self::assertSame('2', $request->getCookieValue('1'));
        self::assertNull($request->getCookieValue('foo'));
        self::assertNull($request->getCookieValue('baz'));
    }

    /**
     * Test getRawContentMethod with no content set.
     */
    public function testGetRawContentWithNoContentSet()
    {
        $request = new Request();

        self::assertSame('', $request->getRawContent());
    }

    /**
     * Test getRawContentMethod with content set.
     */
    public function testGetRawContentWithContentSet()
    {
        FakeFileGetContentsPhpInput::setContent('<foo><bar>Baz</bar></foo>');

        $request = new Request();

        self::assertSame('<foo><bar>Baz</bar></foo>', $request->getRawContent());
        self::assertSame('<foo><bar>Baz</bar></foo>', $request->getRawContent()); // Can be fetched multiple times.
    }

    /**
     * Test getClientIp with no address set.
     */
    public function testGetClientIpWithNoAddressSet()
    {
        $request = new Request();

        self::assertSame('0.0.0.0', $request->getClientIp()->__toString());
    }

    /**
     * Test getClientIp with invalid address set.
     */
    public function testGetClientIpWithInvalidAddressSet()
    {
        $_SERVER['REMOTE_ADDR'] = 'FooBar';

        $request = new Request();

        self::assertSame('0.0.0.0', $request->getClientIp()->__toString());
    }

    /**
     * Test getClientIp with address set.
     */
    public function testGetClientIpWithAddressSet()
    {
        $_SERVER['REMOTE_ADDR'] = '10.20.30.40';

        $request = new Request();

        self::assertSame('10.20.30.40', $request->getClientIp()->__toString());
    }

    /**
     * Test getReferrer with no referrer set.
     */
    public function testGetReferrerWithNoReferrerSet()
    {
        $request = new Request();

        self::assertNull($request->getReferrer());
    }

    /**
     * Test getReferrer with invalid referrer.
     */
    public function testGetReferrerWithInvalidReferrer()
    {
        $_SERVER['HTTP_REFERER'] = 'FooBar';

        $request = new Request();

        self::assertNull($request->getReferrer());
    }

    /**
     * Test getReferrer with valid referrer.
     */
    public function testGetReferrerWithValidReferrer()
    {
        $_SERVER['HTTP_REFERER'] = 'https://example.com:8080/foo/bar?baz';

        $request = new Request();

        self::assertSame('https://example.com:8080/foo/bar?baz', $request->getReferrer()->__toString());
    }

    /**
     * Test getSessionItems method with no session items set.
     */
    public function testGetSessionItemsWithNoSessionItemsSet()
    {
        $request = new Request();
        $sessionItems = $request->getSessionItems();

        self::assertSame([], iterator_to_array($sessionItems));
        self::assertSame([], $_SESSION);
    }

    /**
     * Test getSessionItems method with session items set.
     */
    public function testGetSessionItemsWithSessionItemsSet()
    {
        $_SESSION = [
            'Foo' => 'Bar',
        ];

        $request = new Request();
        $sessionItems = $request->getSessionItems();

        self::assertSame(['Foo' => 'Bar'], iterator_to_array($sessionItems));
        self::assertSame(['Foo' => 'Bar'], $_SESSION);
    }

    /**
     * Test setSessionItem method.
     */
    public function testSetSessionItem()
    {
        $request = new Request();
        $request->setSessionItem('Foo', 1);
        $request->setSessionItem('Bar', false);

        $sessionItems = $request->getSessionItems();

        self::assertSame(['Foo' => 1, 'Bar' => false], iterator_to_array($sessionItems));
        self::assertSame(['Foo' => 1, 'Bar' => false], $_SESSION);
    }

    /**
     * Test getSessionItem method.
     */
    public function testGetSessionItem()
    {
        $_SESSION = [
            'Foo' => 'Bar',
            'Baz' => [true, false],
        ];

        $request = new Request();

        self::assertSame('Bar', $request->getSessionItem('Foo'));
        self::assertSame([true, false], $request->getSessionItem('Baz'));
        self::assertNull($request->getSessionItem('Bar'));
        self::assertNull($request->getSessionItem('foo'));
        self::assertSame(['Foo' => 'Bar', 'Baz' => [true, false]], $_SESSION);
    }

    /**
     * Test removeSessionItem method.
     */
    public function testRemoveSessionItem()
    {
        $_SESSION = [
            'Foo' => 'Bar',
            'Baz' => [true, false],
        ];

        $request = new Request();

        $request->removeSessionItem('Bar');
        $request->removeSessionItem('Baz');

        self::assertSame('Bar', $request->getSessionItem('Foo'));
        self::assertNull($request->getSessionItem('Baz'));
        self::assertNull($request->getSessionItem('Bar'));
        self::assertNull($request->getSessionItem('foo'));
        self::assertSame(['Foo' => 'Bar'], $_SESSION);
    }

    /**
     * Test session options for non secure request (http).
     */
    public function testSessionOptionsForNonSecureRequest()
    {
        $request = new Request();
        $request->setSessionItem('Foo', 'Bar');

        self::assertSame(['cookie_httponly' => true, 'use_strict_mode' => true], FakeSession::getOptions());
    }

    /**
     * Test session options for secure request (https).
     */
    public function testSessionOptionsForSecureRequest()
    {
        $_SERVER['HTTPS'] = 'On';

        $request = new Request();
        $request->setSessionItem('Foo', 'Bar');

        self::assertSame(['cookie_httponly' => true, 'use_strict_mode' => true, 'cookie_secure' => true], FakeSession::getOptions());
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->originalServerArray = $_SERVER;
        $_SERVER = [
            'HTTP_HOST'      => 'example.com',
            'REQUEST_URI'    => '/foo/bar',
            'REQUEST_METHOD' => 'GET',
        ];

        FakeIsUploadedFile::enable();
        FakeFileGetContentsPhpInput::enable();
        FakeSession::enable();
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        FakeIsUploadedFile::disable();
        FakeFileGetContentsPhpInput::disable();
        FakeSession::disable();

        $_GET = [];
        $_POST = [];
        $_FILES = [];
        $_COOKIE = [];
        $_SERVER = $this->originalServerArray;
    }

    /**
     * @var array The original server array.
     */
    private $originalServerArray;
}
