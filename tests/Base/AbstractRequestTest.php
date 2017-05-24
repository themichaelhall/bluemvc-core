<?php

require_once __DIR__ . '/../Helpers/TestRequests/BasicTestRequest.php';

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ParameterCollection;
use BlueMvc\Core\Http\Method;
use DataTypes\Url;

/**
 * Test AbstractRequest class (via derived test class).
 */
class AbstractRequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getUrl method.
     */
    public function testGetUrl()
    {
        $this->assertSame('https://domain.com/foo/bar', $this->myRequest->getUrl()->__toString());
    }

    /**
     * Test getMethod method.
     */
    public function testGetMethod()
    {
        $this->assertSame('POST', $this->myRequest->getMethod()->__toString());
    }

    /**
     * Test setUrl method.
     */
    public function testSetUrl()
    {
        $this->myRequest->setUrl(Url::parse('http://foo.com:8080/bar?baz'));

        $this->assertSame('http://foo.com:8080/bar?baz', $this->myRequest->getUrl()->__toString());
    }

    /**
     * Test setMethod method.
     */
    public function testSetMethod()
    {
        $this->myRequest->setMethod(new Method('GET'));

        $this->assertSame('GET', $this->myRequest->getMethod()->__toString());
    }

    /**
     * Test getHeaders method.
     */
    public function testGetHeaders()
    {
        $this->assertSame([], iterator_to_array($this->myRequest->getHeaders()));
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

        $this->assertSame(['Accept-Encoding' => 'gzip, deflate', 'Host' => 'localhost'], iterator_to_array($this->myRequest->getHeaders()));
    }

    /**
     * Test setHeader method.
     */
    public function testSetHeader()
    {
        $this->myRequest->setHeader('Accept-Language', 'en-US,en;q=0.5');

        $this->assertSame(['Accept-Language' => 'en-US,en;q=0.5'], iterator_to_array($this->myRequest->getHeaders()));
    }

    /**
     * Test addHeader method.
     */
    public function testAddHeader()
    {
        $this->myRequest->setHeader('Accept-Language', 'en-US');
        $this->myRequest->addHeader('Accept-Language', 'en');

        $this->assertSame(['Accept-Language' => 'en-US, en'], iterator_to_array($this->myRequest->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $this->myRequest->setHeader('Accept-Language', 'en-US,en;q=0.5');

        $this->assertSame('en-US,en;q=0.5', $this->myRequest->getHeader('accept-language'));
        $this->assertNull($this->myRequest->getHeader('Foo-Bar'));
    }

    /**
     * Test getUserAgent method without user agent set.
     */
    public function testGetUserAgentWithoutUserAgentSet()
    {
        $this->assertSame('', $this->myRequest->getUserAgent());
    }

    /**
     * Test getUserAgent method with user agent set.
     */
    public function testGetUserAgentWithUserAgentSet()
    {
        $this->myRequest->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');

        $this->assertSame('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', $this->myRequest->getUserAgent());
    }

    /**
     * Test getFormParameters method.
     */
    public function testGetFormParameters()
    {
        $this->assertSame([], iterator_to_array($this->myRequest->getFormParameters()));
    }

    /**
     * Test setFormParameters method.
     */
    public function testSetFormParameters()
    {
        $formParameters = new ParameterCollection();
        $formParameters->set('Foo', 'Bar');
        $this->myRequest->setFormParameters($formParameters);

        $this->assertSame(['Foo' => 'Bar'], iterator_to_array($this->myRequest->getFormParameters()));
    }

    /**
     * Test setFormParameter method.
     */
    public function testSetFormParameter()
    {
        $this->myRequest->setFormParameter('Foo', 'Bar');

        $this->assertSame(['Foo' => 'Bar'], iterator_to_array($this->myRequest->getFormParameters()));
    }

    /**
     * Test getFormParameter method.
     */
    public function testGetFormParameter()
    {
        $this->myRequest->setFormParameter('Foo', 'Bar');

        $this->assertSame('Bar', $this->myRequest->getFormParameter('Foo'));
        $this->assertNull($this->myRequest->getFormParameter('Bar'));
    }

    /**
     * Test getQueryParameters method.
     */
    public function testGetQueryParameters()
    {
        $this->assertSame([], iterator_to_array($this->myRequest->getQueryParameters()));
    }

    /**
     * Test setQueryParameters method.
     */
    public function testSetQueryParameters()
    {
        $queryParameters = new ParameterCollection();
        $queryParameters->set('foo', 'bar');
        $this->myRequest->setQueryParameters($queryParameters);

        $this->assertSame(['foo' => 'bar'], iterator_to_array($this->myRequest->getQueryParameters()));
    }

    /**
     * Test setQueryParameter method.
     */
    public function testSetQueryParameter()
    {
        $this->myRequest->setQueryParameter('Foo', 'Bar');

        $this->assertSame(['Foo' => 'Bar'], iterator_to_array($this->myRequest->getQueryParameters()));
    }

    /**
     * Test getQueryParameter method.
     */
    public function testGetQueryParameter()
    {
        $this->myRequest->setQueryParameter('Foo', 'Bar');

        $this->assertSame('Bar', $this->myRequest->getQueryParameter('Foo'));
        $this->assertNull($this->myRequest->getQueryParameter('Bar'));
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
