<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Exceptions\ServerEnvironmentException;
use BlueMvc\Core\Request;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeIsUploadedFile;

/**
 * Test Request class.
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getUrl method.
     */
    public function testGetUrl()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame('http://www.domain.com/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method for https request.
     */
    public function testGetUrlForHttpsRequest()
    {
        $request = new Request(
            [
                'HTTPS'          => 'On',
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame('https://www.domain.com/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with port.
     */
    public function testGetUrlWithPort()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com:8080',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame('http://www.domain.com:8080/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with empty query string.
     */
    public function testGetUrlWithEmptyQueryString()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar?',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame('http://www.domain.com/foo/bar?', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with query string.
     */
    public function testGetUrlWithQueryString()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar?baz=true',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame('http://www.domain.com/foo/bar?baz=true', $request->getUrl()->__toString());
    }

    /**
     * Test getMethod method.
     */
    public function testGetMethod()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'POST',
            ]
        );

        self::assertSame('POST', $request->getMethod()->__toString());
    }

    /**
     * Test getHeaders method.
     */
    public function testGetHeaders()
    {
        $request = new Request(
            [
                'HTTP_HOST'            => 'www.domain.com',
                'REQUEST_URI'          => '/foo/bar',
                'REQUEST_METHOD'       => 'GET',
                'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
            ]
        );

        self::assertSame(['Host' => 'www.domain.com', 'Accept-Encoding' => 'gzip, deflate'], iterator_to_array($request->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $request = new Request(
            [
                'HTTP_HOST'            => 'www.domain.com',
                'REQUEST_URI'          => '/foo/bar',
                'REQUEST_METHOD'       => 'GET',
                'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
            ]
        );

        self::assertSame('gzip, deflate', $request->getHeader('Accept-Encoding'));
        self::assertNull($request->getHeader('Foo-Bar'));
    }

    /**
     * Test getUserAgent method without user agent set.
     */
    public function testGetUserAgentWithoutUserAgentSet()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame('', $request->getUserAgent());
    }

    /**
     * Test getUserAgent method with user agent set.
     */
    public function testGetUserAgentWithUserAgentSet()
    {
        $request = new Request(
            [
                'HTTP_HOST'       => 'www.domain.com',
                'REQUEST_URI'     => '/foo/bar',
                'REQUEST_METHOD'  => 'GET',
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
            ]
        );

        self::assertSame('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', $request->getUserAgent());
    }

    /**
     * Test getFormParameters method without form parameters set.
     */
    public function testGetFormParametersWithoutFormParametersSet()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame([], iterator_to_array($request->getFormParameters()));
    }

    /**
     * Test getFormParameters method with form parameters set.
     */
    public function testGetFormParametersWithFormParametersSet()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
                'Foo' => '1',
                'Bar' => ['2', '3'],
            ]
        );

        self::assertSame(['Foo' => '1', 'Bar' => '2'], iterator_to_array($request->getFormParameters()));
    }

    /**
     * Test getFormParameter method.
     */
    public function testGetFormParameter()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
                'Foo' => '1',
                'Bar' => ['2', '3'],
            ]
        );

        self::assertSame('1', $request->getFormParameter('Foo'));
        self::assertSame('2', $request->getFormParameter('Bar'));
        self::assertNull($request->getFormParameter('Baz'));
    }

    /**
     * Test getQueryParameters method without query parameters set.
     */
    public function testGetQueryParametersWithoutQueryParametersSet()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame([], iterator_to_array($request->getQueryParameters()));
    }

    /**
     * Test getQueryParameters method with query parameters set.
     */
    public function testGetQueryParametersWithQueryParametersSet()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
                'Foo' => '1',
                'Bar' => ['2', '3'],
            ]
        );

        self::assertSame(['Foo' => '1', 'Bar' => '2'], iterator_to_array($request->getQueryParameters()));
    }

    /**
     * Test getQueryParameter method.
     */
    public function testGetQueryParameter()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
                'Foo' => '1',
                'Bar' => ['2', '3'],
            ]
        );

        self::assertSame('1', $request->getQueryParameter('Foo'));
        self::assertSame('2', $request->getQueryParameter('Bar'));
        self::assertNull($request->getQueryParameter('Baz'));
    }

    /**
     * Test getQueryParameter method with numeric parameter.
     */
    public function testGetQueryParameterWithNumericParameters()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
                1 => 2,
            ]
        );

        self::assertSame('2', $request->getQueryParameter('1'));
    }

    /**
     * Test getFormParameter method with numeric parameter.
     */
    public function testGetFormParameterWithNumericParameters()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
                1 => 2,
            ]
        );

        self::assertSame('2', $request->getFormParameter('1'));
    }

    /**
     * Test getUploadedFiles method with no uploaded files set.
     */
    public function testGetUploadedFilesWithNoUploadedFiles()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame([], iterator_to_array($request->getUploadedFiles()));
    }

    /**
     * Test getUploadedFiles method with uploaded files set.
     */
    public function testGetUploadedFilesWithUploadedFiles()
    {
        $DS = DIRECTORY_SEPARATOR;

        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
            ],
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
            ]
        );

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

        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
            ],
            [
                'foo' => [
                    'name'     => 'Documents/Foo.doc',
                    'type'     => 'application/msword',
                    'tmp_name' => '/tmp/foo123.doc',
                    'size'     => '56789',
                    'error'    => '0',
                ],
            ]
        );

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
            $request = new Request(
                [
                    'HTTP_HOST'      => 'www.domain.com',
                    'REQUEST_URI'    => '/foo/bar',
                    'REQUEST_METHOD' => 'GET',
                ],
                [
                ],
                [
                ],
                [
                    'foo' => [
                        'name'     => 'Foo.txt',
                        'type'     => 'text/plain',
                        'tmp_name' => '/tmp/foo.txt',
                        'size'     => '100',
                        'error'    => $error,
                    ],
                ]
            );
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

        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
            ],
            [
                'foo' => [
                    'name'     => ['Documents/Foo.doc', 'Documents/Bar.txt'],
                    'type'     => ['application/msword', 'text/plain'],
                    'tmp_name' => ['/tmp/foo123.doc', '/tmp/bar456.doc'],
                    'size'     => [56789, 1234],
                    'error'    => [0, 0],
                ],
            ]
        );

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
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
            ],
            [
                'foo' => [
                    'name'     => 'Documents/Foo.doc',
                    'type'     => 'application/msword',
                    'tmp_name' => '/tmp/foo123.not-uploaded',
                    'size'     => '56789',
                    'error'    => '0',
                ],
            ]
        );

        self::assertEmpty($request->getUploadedFiles());
        self::assertNull($request->getUploadedFile('foo'));
    }

    /**
     * Test getCookies method with no cookies set.
     */
    public function testGetCookiesWithNoCookiesSet()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        self::assertSame([], iterator_to_array($request->getCookies()));
    }

    /**
     * Test getCookies method with cookies set.
     */
    public function testGetCookiesWithCookiesSet()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
            ],
            [
            ],
            [
                'Foo' => 'Bar',
                1     => 2,
            ]
        );

        $cookies = $request->getCookies();

        self::assertSame('Bar', $cookies->get('Foo')->getValue());
        self::assertSame('2', $cookies->get('1')->getValue());
    }

    /**
     * Test getCookie method.
     */
    public function testGetCookie()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ],
            [
            ],
            [
            ],
            [
            ],
            [
                'Foo' => 'Bar',
                1     => 2,
            ]
        );

        self::assertSame('Bar', $request->getCookie('Foo')->getValue());
        self::assertSame('2', $request->getCookie('1')->getValue());
        self::assertNull($request->getCookie('foo'));
        self::assertNull($request->getCookie('baz'));
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        FakeIsUploadedFile::enable();
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        FakeIsUploadedFile::disable();
    }
}
