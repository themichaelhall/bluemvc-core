<?php

namespace BlueMvc\Core\Tests\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ParameterCollection;
use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\UploadedFile;
use DataTypes\FilePath;
use DataTypes\Url;

/**
 * Test AbstractRequest class (via derived test class).
 */
class AbstractRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getUrl method.
     */
    public function testGetUrl()
    {
        self::assertSame('https://domain.com/foo/bar', $this->myRequest->getUrl()->__toString());
    }

    /**
     * Test getMethod method.
     */
    public function testGetMethod()
    {
        self::assertSame('POST', $this->myRequest->getMethod()->__toString());
    }

    /**
     * Test setUrl method.
     */
    public function testSetUrl()
    {
        $this->myRequest->setUrl(Url::parse('http://foo.com:8080/bar?baz'));

        self::assertSame('http://foo.com:8080/bar?baz', $this->myRequest->getUrl()->__toString());
    }

    /**
     * Test setMethod method.
     */
    public function testSetMethod()
    {
        $this->myRequest->setMethod(new Method('GET'));

        self::assertSame('GET', $this->myRequest->getMethod()->__toString());
    }

    /**
     * Test getHeaders method.
     */
    public function testGetHeaders()
    {
        self::assertSame([], iterator_to_array($this->myRequest->getHeaders()));
    }

    /**
     * Test setHeaders method.
     */
    public function testSetHeaders()
    {
        $headers = new HeaderCollection();
        $headers->add('Accept-Encoding', 'gzip, deflate');
        $headers->add('Host', 'localhost');

        $this->myRequest->setHeaders($headers);

        self::assertSame(['Accept-Encoding' => 'gzip, deflate', 'Host' => 'localhost'], iterator_to_array($this->myRequest->getHeaders()));
    }

    /**
     * Test setHeader method.
     */
    public function testSetHeader()
    {
        $this->myRequest->setHeader('Accept-Language', 'en-US,en;q=0.5');

        self::assertSame(['Accept-Language' => 'en-US,en;q=0.5'], iterator_to_array($this->myRequest->getHeaders()));
    }

    /**
     * Test addHeader method.
     */
    public function testAddHeader()
    {
        $this->myRequest->setHeader('Accept-Language', 'en-US');
        $this->myRequest->addHeader('Accept-Language', 'en');

        self::assertSame(['Accept-Language' => 'en-US, en'], iterator_to_array($this->myRequest->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $this->myRequest->setHeader('Accept-Language', 'en-US,en;q=0.5');

        self::assertSame('en-US,en;q=0.5', $this->myRequest->getHeader('accept-language'));
        self::assertNull($this->myRequest->getHeader('Foo-Bar'));
    }

    /**
     * Test getUserAgent method without user agent set.
     */
    public function testGetUserAgentWithoutUserAgentSet()
    {
        self::assertSame('', $this->myRequest->getUserAgent());
    }

    /**
     * Test getUserAgent method with user agent set.
     */
    public function testGetUserAgentWithUserAgentSet()
    {
        $this->myRequest->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');

        self::assertSame('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', $this->myRequest->getUserAgent());
    }

    /**
     * Test getFormParameters method.
     */
    public function testGetFormParameters()
    {
        self::assertSame([], iterator_to_array($this->myRequest->getFormParameters()));
    }

    /**
     * Test setFormParameters method.
     */
    public function testSetFormParameters()
    {
        $formParameters = new ParameterCollection();
        $formParameters->set('Foo', 'Bar');
        $this->myRequest->setFormParameters($formParameters);

        self::assertSame(['Foo' => 'Bar'], iterator_to_array($this->myRequest->getFormParameters()));
    }

    /**
     * Test setFormParameter method.
     */
    public function testSetFormParameter()
    {
        $this->myRequest->setFormParameter('Foo', 'Bar');

        self::assertSame(['Foo' => 'Bar'], iterator_to_array($this->myRequest->getFormParameters()));
    }

    /**
     * Test getFormParameter method.
     */
    public function testGetFormParameter()
    {
        $this->myRequest->setFormParameter('Foo', 'Bar');

        self::assertSame('Bar', $this->myRequest->getFormParameter('Foo'));
        self::assertNull($this->myRequest->getFormParameter('Bar'));
    }

    /**
     * Test getQueryParameters method.
     */
    public function testGetQueryParameters()
    {
        self::assertSame([], iterator_to_array($this->myRequest->getQueryParameters()));
    }

    /**
     * Test setQueryParameters method.
     */
    public function testSetQueryParameters()
    {
        $queryParameters = new ParameterCollection();
        $queryParameters->set('foo', 'bar');
        $this->myRequest->setQueryParameters($queryParameters);

        self::assertSame(['foo' => 'bar'], iterator_to_array($this->myRequest->getQueryParameters()));
    }

    /**
     * Test setQueryParameter method.
     */
    public function testSetQueryParameter()
    {
        $this->myRequest->setQueryParameter('Foo', 'Bar');

        self::assertSame(['Foo' => 'Bar'], iterator_to_array($this->myRequest->getQueryParameters()));
    }

    /**
     * Test getQueryParameter method.
     */
    public function testGetQueryParameter()
    {
        $this->myRequest->setQueryParameter('Foo', 'Bar');

        self::assertSame('Bar', $this->myRequest->getQueryParameter('Foo'));
        self::assertNull($this->myRequest->getQueryParameter('Bar'));
    }

    /**
     * Test getUploadedFiles method.
     */
    public function testGetUploadedFiles()
    {
        self::assertSame([], iterator_to_array($this->myRequest->getUploadedFiles()));
    }

    /**
     * Test setUploadedFiles method.
     */
    public function testSetUploadedFiles()
    {
        $fileFoo = new UploadedFile(FilePath::parse('/tmp/foo'), 'Foo.txt', 1000);
        $fileBar = new UploadedFile(FilePath::parse('/tmp/bar'), 'Bar.txt', 1000);

        $uploadedFiles = new UploadedFileCollection();
        $uploadedFiles->set('foo', $fileFoo);
        $uploadedFiles->set('bar', $fileBar);

        $this->myRequest->setUploadedFiles($uploadedFiles);

        self::assertSame(['foo' => $fileFoo, 'bar' => $fileBar], iterator_to_array($this->myRequest->getUploadedFiles()));
    }

    /**
     * Test getUploadedFile method.
     */
    public function testGetUploadedFile()
    {
        $fileFoo = new UploadedFile(FilePath::parse('/tmp/foo'), 'Foo.txt', 1000);
        $fileBar = new UploadedFile(FilePath::parse('/tmp/bar'), 'Bar.txt', 1000);

        $uploadedFiles = new UploadedFileCollection();
        $uploadedFiles->set('FOO', $fileFoo);
        $uploadedFiles->set('bar', $fileBar);

        $this->myRequest->setUploadedFiles($uploadedFiles);

        self::assertSame($fileFoo, $this->myRequest->getUploadedFile('FOO'));
        self::assertSame($fileBar, $this->myRequest->getUploadedFile('bar'));
    }

    /**
     * Test setUploadedFile method.
     */
    public function testSetUploadedFile()
    {
        $fileFoo = new UploadedFile(FilePath::parse('/tmp/foo'), 'Foo.txt', 1000);

        $this->myRequest->setUploadedFile('foo', $fileFoo);

        self::assertSame(['foo' => $fileFoo], iterator_to_array($this->myRequest->getUploadedFiles()));
    }

    /**
     * Test getCookies method.
     */
    public function testGetCookies()
    {
        self::assertSame([], iterator_to_array($this->myRequest->getCookies()));
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->myRequest = new BasicTestRequest(Url::parse('https://domain.com/foo/bar'), new Method('POST'));
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->myRequest = null;
    }

    /**
     * @var BasicTestRequest My test request.
     */
    private $myRequest;
}
