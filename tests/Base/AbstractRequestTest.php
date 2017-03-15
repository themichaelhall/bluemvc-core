<?php

require_once __DIR__ . '/../Helpers/TestRequests/BasicTestRequest.php';

use BlueMvc\Core\Collections\HeaderCollection;
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
