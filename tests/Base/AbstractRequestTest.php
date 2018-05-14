<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ParameterCollection;
use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\RequestCookie;
use BlueMvc\Core\Tests\Helpers\TestCollections\BasicTestSessionItemCollection;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\UploadedFile;
use DataTypes\FilePath;
use DataTypes\IPAddress;
use DataTypes\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test AbstractRequest class (via derived test class).
 */
class AbstractRequestTest extends TestCase
{
    /**
     * Test getUrl method.
     */
    public function testGetUrl()
    {
        self::assertSame('https://domain.com/foo/bar', $this->request->getUrl()->__toString());
    }

    /**
     * Test getMethod method.
     */
    public function testGetMethod()
    {
        self::assertSame('POST', $this->request->getMethod()->__toString());
    }

    /**
     * Test setUrl method.
     */
    public function testSetUrl()
    {
        $this->request->setUrl(Url::parse('http://foo.com:8080/bar?baz'));

        self::assertSame('http://foo.com:8080/bar?baz', $this->request->getUrl()->__toString());
    }

    /**
     * Test setMethod method.
     */
    public function testSetMethod()
    {
        $this->request->setMethod(new Method('GET'));

        self::assertSame('GET', $this->request->getMethod()->__toString());
    }

    /**
     * Test getHeaders method.
     */
    public function testGetHeaders()
    {
        self::assertSame([], iterator_to_array($this->request->getHeaders()));
    }

    /**
     * Test setHeaders method.
     */
    public function testSetHeaders()
    {
        $headers = new HeaderCollection();
        $headers->add('Accept-Encoding', 'gzip, deflate');
        $headers->add('Host', 'localhost');

        $this->request->setHeaders($headers);

        self::assertSame(['Accept-Encoding' => 'gzip, deflate', 'Host' => 'localhost'], iterator_to_array($this->request->getHeaders()));
    }

    /**
     * Test setHeader method.
     */
    public function testSetHeader()
    {
        $this->request->setHeader('Accept-Language', 'en-US,en;q=0.5');

        self::assertSame(['Accept-Language' => 'en-US,en;q=0.5'], iterator_to_array($this->request->getHeaders()));
    }

    /**
     * Test addHeader method.
     */
    public function testAddHeader()
    {
        $this->request->setHeader('Accept-Language', 'en-US');
        $this->request->addHeader('Accept-Language', 'en');

        self::assertSame(['Accept-Language' => 'en-US, en'], iterator_to_array($this->request->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $this->request->setHeader('Accept-Language', 'en-US,en;q=0.5');

        self::assertSame('en-US,en;q=0.5', $this->request->getHeader('accept-language'));
        self::assertNull($this->request->getHeader('Foo-Bar'));
    }

    /**
     * Test getUserAgent method without user agent set.
     */
    public function testGetUserAgentWithoutUserAgentSet()
    {
        self::assertSame('', $this->request->getUserAgent());
    }

    /**
     * Test getUserAgent method with user agent set.
     */
    public function testGetUserAgentWithUserAgentSet()
    {
        $this->request->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36');

        self::assertSame('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36', $this->request->getUserAgent());
    }

    /**
     * Test getFormParameters method.
     */
    public function testGetFormParameters()
    {
        self::assertSame([], iterator_to_array($this->request->getFormParameters()));
    }

    /**
     * Test setFormParameters method.
     */
    public function testSetFormParameters()
    {
        $formParameters = new ParameterCollection();
        $formParameters->set('Foo', 'Bar');
        $this->request->setFormParameters($formParameters);

        self::assertSame(['Foo' => 'Bar'], iterator_to_array($this->request->getFormParameters()));
    }

    /**
     * Test setFormParameter method.
     */
    public function testSetFormParameter()
    {
        $this->request->setFormParameter('Foo', 'Bar');

        self::assertSame(['Foo' => 'Bar'], iterator_to_array($this->request->getFormParameters()));
    }

    /**
     * Test getFormParameter method.
     */
    public function testGetFormParameter()
    {
        $this->request->setFormParameter('Foo', 'Bar');

        self::assertSame('Bar', $this->request->getFormParameter('Foo'));
        self::assertNull($this->request->getFormParameter('Bar'));
    }

    /**
     * Test getQueryParameters method.
     */
    public function testGetQueryParameters()
    {
        self::assertSame([], iterator_to_array($this->request->getQueryParameters()));
    }

    /**
     * Test setQueryParameters method.
     */
    public function testSetQueryParameters()
    {
        $queryParameters = new ParameterCollection();
        $queryParameters->set('foo', 'bar');
        $this->request->setQueryParameters($queryParameters);

        self::assertSame(['foo' => 'bar'], iterator_to_array($this->request->getQueryParameters()));
    }

    /**
     * Test setQueryParameter method.
     */
    public function testSetQueryParameter()
    {
        $this->request->setQueryParameter('Foo', 'Bar');

        self::assertSame(['Foo' => 'Bar'], iterator_to_array($this->request->getQueryParameters()));
    }

    /**
     * Test getQueryParameter method.
     */
    public function testGetQueryParameter()
    {
        $this->request->setQueryParameter('Foo', 'Bar');

        self::assertSame('Bar', $this->request->getQueryParameter('Foo'));
        self::assertNull($this->request->getQueryParameter('Bar'));
    }

    /**
     * Test getUploadedFiles method.
     */
    public function testGetUploadedFiles()
    {
        self::assertSame([], iterator_to_array($this->request->getUploadedFiles()));
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

        $this->request->setUploadedFiles($uploadedFiles);

        self::assertSame(['foo' => $fileFoo, 'bar' => $fileBar], iterator_to_array($this->request->getUploadedFiles()));
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

        $this->request->setUploadedFiles($uploadedFiles);

        self::assertSame($fileFoo, $this->request->getUploadedFile('FOO'));
        self::assertSame($fileBar, $this->request->getUploadedFile('bar'));
    }

    /**
     * Test setUploadedFile method.
     */
    public function testSetUploadedFile()
    {
        $fileFoo = new UploadedFile(FilePath::parse('/tmp/foo'), 'Foo.txt', 1000);

        $this->request->setUploadedFile('foo', $fileFoo);

        self::assertSame(['foo' => $fileFoo], iterator_to_array($this->request->getUploadedFiles()));
    }

    /**
     * Test getCookies method.
     */
    public function testGetCookies()
    {
        self::assertSame([], iterator_to_array($this->request->getCookies()));
    }

    /**
     * Test setCookies method.
     */
    public function testSetCookies()
    {
        $fooCookie = new RequestCookie('foo-value');
        $barCookie = new RequestCookie('bar-value');

        $cookies = new RequestCookieCollection();
        $cookies->set('foo', $fooCookie);
        $cookies->set('bar', $barCookie);

        $this->request->setCookies($cookies);

        self::assertSame(['foo' => $fooCookie, 'bar' => $barCookie], iterator_to_array($this->request->getCookies()));
    }

    /**
     * Test setCookie method.
     */
    public function testSetCookie()
    {
        $fooCookie = new RequestCookie('foo-value');
        $barCookie = new RequestCookie('bar-value');

        $this->request->setCookie('foo', $barCookie);
        $this->request->setCookie('bar', $barCookie);
        $this->request->setCookie('foo', $fooCookie);

        self::assertSame(['foo' => $fooCookie, 'bar' => $barCookie], iterator_to_array($this->request->getCookies()));
    }

    /**
     * Test getCookie method.
     */
    public function testGetCookie()
    {
        $fooCookie = new RequestCookie('foo-value');
        $barCookie = new RequestCookie('bar-value');

        $cookies = new RequestCookieCollection();
        $cookies->set('foo', $fooCookie);
        $cookies->set('bar', $barCookie);

        $this->request->setCookies($cookies);

        self::assertSame($fooCookie, $this->request->getCookie('foo'));
        self::assertSame($barCookie, $this->request->getCookie('bar'));
        self::assertNull($this->request->getCookie('Foo'));
        self::assertNull($this->request->getCookie('baz'));
    }

    /**
     * Test getCookieValue method.
     */
    public function testGetCookieValue()
    {
        $fooCookie = new RequestCookie('foo-value');
        $barCookie = new RequestCookie('bar-value');

        $cookies = new RequestCookieCollection();
        $cookies->set('foo', $fooCookie);
        $cookies->set('bar', $barCookie);

        $this->request->setCookies($cookies);

        self::assertSame('foo-value', $this->request->getCookieValue('foo'));
        self::assertSame('bar-value', $this->request->getCookieValue('bar'));
        self::assertNull($this->request->getCookieValue('Foo'));
        self::assertNull($this->request->getCookieValue('baz'));
    }

    /**
     * Test getRawContent method.
     */
    public function testGetRawContent()
    {
        self::assertSame('', $this->request->getRawContent());
    }

    /**
     * Test setRawContent method.
     */
    public function testSetRawContent()
    {
        $this->request->setRawContent('{"Foo": "Bar"}');

        self::assertSame('{"Foo": "Bar"}', $this->request->getRawContent());
    }

    /**
     * Test getReferrer method with empty referrer.
     */
    public function testGetReferrerWithEmptyReferrer()
    {
        self::assertNull($this->request->getReferrer());
    }

    /**
     * Test getReferrer method with invalid referrer.
     */
    public function testGetReferrerWithInvalidReferrer()
    {
        $this->request->setHeader('Referer', 'foo');

        self::assertNull($this->request->getReferrer());
    }

    /**
     * Test getReferrer method with empty referrer.
     */
    public function testGetReferrerWithValidReferrer()
    {
        $this->request->setHeader('Referer', 'https://example.com/foo');

        self::assertSame('https://example.com/foo', $this->request->getReferrer()->__toString());
    }

    /**
     * Test getClientIp method.
     */
    public function testGetClientIp()
    {
        self::assertSame('0.0.0.0', $this->request->getClientIp()->__toString());
    }

    /**
     * Test setClientIp method.
     */
    public function testSetClientIp()
    {
        $clientIp = IPAddress::parse('1.2.3.4');
        $this->request->setClientIp($clientIp);

        self::assertSame($clientIp, $this->request->getClientIp());
    }

    /**
     * Test getSessionItems method.
     */
    public function testGetSessionItems()
    {
        $sessionItems = $this->request->getSessionItems();

        self::assertSame([], iterator_to_array($sessionItems));
    }

    /**
     * Test setSessionItem method.
     */
    public function testSetSessionItem()
    {
        $this->request->setSessionItem('Foo', [1, 2]);
        $this->request->setSessionItem('Bar', true);

        $sessionItems = $this->request->getSessionItems();

        self::assertSame(['Foo' => [1, 2], 'Bar' => true], iterator_to_array($sessionItems));
    }

    /**
     * Test setSessionItems method.
     */
    public function testSetSessionItems()
    {
        $sessionItems = new BasicTestSessionItemCollection();
        $sessionItems->set('Foo', 1);
        $sessionItems->set('Bar', 2);

        $this->request->setSessionItems($sessionItems);

        self::assertSame(['Foo' => 1, 'Bar' => 2], iterator_to_array($this->request->getSessionItems()));
    }

    /**
     * Test getSessionItem method.
     */
    public function testGetSessionItem()
    {
        $this->request->setSessionItem('Foo', [1, 2]);
        $this->request->setSessionItem('Bar', true);

        self::assertSame([1, 2], $this->request->getSessionItem('Foo'));
        self::assertSame(true, $this->request->getSessionItem('Bar'));
        self::assertNull($this->request->getSessionItem('Baz'));
        self::assertNull($this->request->getSessionItem('bar'));
    }

    /**
     * Test removeSessionItem method.
     */
    public function testRemoveSessionItem()
    {
        $this->request->setSessionItem('Foo', [1, 2]);
        $this->request->setSessionItem('Bar', true);

        $this->request->removeSessionItem('Bar');
        $this->request->removeSessionItem('Baz');

        self::assertSame([1, 2], $this->request->getSessionItem('Foo'));
        self::assertNull($this->request->getSessionItem('Bar'));
        self::assertNull($this->request->getSessionItem('Baz'));
        self::assertNull($this->request->getSessionItem('bar'));
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->request = new BasicTestRequest(Url::parse('https://domain.com/foo/bar'), new Method('POST'));
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->request = null;
    }

    /**
     * @var BasicTestRequest My test request.
     */
    private $request;
}
