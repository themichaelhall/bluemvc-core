<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Http;

use BlueMvc\Core\Exceptions\Http\InvalidStatusCodeException;
use BlueMvc\Core\Http\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 * Test StatusCode class.
 */
class StatusCodeTest extends TestCase
{
    /**
     * Test status codes.
     *
     * @param int    $constant                The status code constant.
     * @param int    $expectedCode            The expected code.
     * @param string $expectedDescription     The expected description.
     * @param string $expectedToString        The expected string representation.
     * @param bool   $expectedIsError         The expected result from isError method.
     * @param bool   $expectedIsInformational The expected result from isInformational method.
     * @param bool   $expectedIsSuccessful    The expected result from isSuccessful method.
     * @param bool   $expectedIsRedirection   The expected result from isRedirection method.
     * @param bool   $expectedIsClientError   The expected result from isClientError method.
     * @param bool   $expectedIsServerError   The expected result from isServerError method.
     *
     * @dataProvider statusCodeProvider
     *
     * @noinspection PhpTooManyParametersInspection
     */
    public function testStatusCodes(
        int $constant,
        int $expectedCode,
        string $expectedDescription,
        string $expectedToString,
        bool $expectedIsError,
        bool $expectedIsInformational,
        bool $expectedIsSuccessful,
        bool $expectedIsRedirection,
        bool $expectedIsClientError,
        bool $expectedIsServerError
    ) {
        $statusCode = new StatusCode($constant);

        self::assertSame($expectedCode, $statusCode->getCode());
        self::assertSame($expectedDescription, $statusCode->getDescription());
        self::assertSame($expectedToString, $statusCode->__toString());
        self::assertSame($expectedIsError, $statusCode->isError());
        self::assertSame($expectedIsInformational, $statusCode->isInformational());
        self::assertSame($expectedIsSuccessful, $statusCode->isSuccessful());
        self::assertSame($expectedIsRedirection, $statusCode->isRedirection());
        self::assertSame($expectedIsClientError, $statusCode->isClientError());
        self::assertSame($expectedIsServerError, $statusCode->isServerError());
    }

    /**
     * Test that an invalid status code is invalid.
     */
    public function testInvalidStatusCodeIsInvalid()
    {
        self::expectException(InvalidStatusCodeException::class);
        self::expectExceptionMessage('Status code 99 is invalid.');

        new StatusCode(99);
    }

    /**
     * Data provider for status code tests.
     *
     * @return array The data.
     */
    public function statusCodeProvider(): array
    {
        return [
            [StatusCode::CONTINUE_, 100, 'Continue', '100 Continue', false, true, false, false, false, false],
            [StatusCode::SWITCHING_PROTOCOLS, 101, 'Switching Protocols', '101 Switching Protocols', false, true, false, false, false, false],
            [StatusCode::PROCESSING, 102, 'Processing', '102 Processing', false, true, false, false, false, false],
            [StatusCode::OK, 200, 'OK', '200 OK', false, false, true, false, false, false],
            [StatusCode::CREATED, 201, 'Created', '201 Created', false, false, true, false, false, false],
            [StatusCode::ACCEPTED, 202, 'Accepted', '202 Accepted', false, false, true, false, false, false],
            [StatusCode::NON_AUTHORITATIVE_INFORMATION, 203, 'Non-Authoritative Information', '203 Non-Authoritative Information', false, false, true, false, false, false],
            [StatusCode::NO_CONTENT, 204, 'No Content', '204 No Content', false, false, true, false, false, false],
            [StatusCode::RESET_CONTENT, 205, 'Reset Content', '205 Reset Content', false, false, true, false, false, false],
            [StatusCode::PARTIAL_CONTENT, 206, 'Partial Content', '206 Partial Content', false, false, true, false, false, false],
            [StatusCode::MULTI_STATUS, 207, 'Multi-Status', '207 Multi-Status', false, false, true, false, false, false],
            [StatusCode::ALREADY_REPORTED, 208, 'Already Reported', '208 Already Reported', false, false, true, false, false, false],
            [StatusCode::IM_USED, 226, 'IM Used', '226 IM Used', false, false, true, false, false, false],
            [StatusCode::MULTIPLE_CHOICES, 300, 'Multiple Choices', '300 Multiple Choices', false, false, false, true, false, false],
            [StatusCode::MOVED_PERMANENTLY, 301, 'Moved Permanently', '301 Moved Permanently', false, false, false, true, false, false],
            [StatusCode::FOUND, 302, 'Found', '302 Found', false, false, false, true, false, false],
            [StatusCode::SEE_OTHER, 303, 'See Other', '303 See Other', false, false, false, true, false, false],
            [StatusCode::NOT_MODIFIED, 304, 'Not Modified', '304 Not Modified', false, false, false, true, false, false],
            [StatusCode::USE_PROXY, 305, 'Use Proxy', '305 Use Proxy', false, false, false, true, false, false],
            [StatusCode::TEMPORARY_REDIRECT, 307, 'Temporary Redirect', '307 Temporary Redirect', false, false, false, true, false, false],
            [StatusCode::PERMANENT_REDIRECT, 308, 'Permanent Redirect', '308 Permanent Redirect', false, false, false, true, false, false],
            [StatusCode::BAD_REQUEST, 400, 'Bad Request', '400 Bad Request', true, false, false, false, true, false],
            [StatusCode::UNAUTHORIZED, 401, 'Unauthorized', '401 Unauthorized', true, false, false, false, true, false],
            [StatusCode::PAYMENT_REQUIRED, 402, 'Payment Required', '402 Payment Required', true, false, false, false, true, false],
            [StatusCode::FORBIDDEN, 403, 'Forbidden', '403 Forbidden', true, false, false, false, true, false],
            [StatusCode::NOT_FOUND, 404, 'Not Found', '404 Not Found', true, false, false, false, true, false],
            [StatusCode::METHOD_NOT_ALLOWED, 405, 'Method Not Allowed', '405 Method Not Allowed', true, false, false, false, true, false],
            [StatusCode::NOT_ACCEPTABLE, 406, 'Not Acceptable', '406 Not Acceptable', true, false, false, false, true, false],
            [StatusCode::PROXY_AUTHENTICATION_REQUIRED, 407, 'Proxy Authentication Required', '407 Proxy Authentication Required', true, false, false, false, true, false],
            [StatusCode::REQUEST_TIMEOUT, 408, 'Request Timeout', '408 Request Timeout', true, false, false, false, true, false],
            [StatusCode::CONFLICT, 409, 'Conflict', '409 Conflict', true, false, false, false, true, false],
            [StatusCode::GONE, 410, 'Gone', '410 Gone', true, false, false, false, true, false],
            [StatusCode::LENGTH_REQUIRED, 411, 'Length Required', '411 Length Required', true, false, false, false, true, false],
            [StatusCode::PRECONDITION_FAILED, 412, 'Precondition Failed', '412 Precondition Failed', true, false, false, false, true, false],
            [StatusCode::PAYLOAD_TOO_LARGE, 413, 'Payload Too Large', '413 Payload Too Large', true, false, false, false, true, false],
            [StatusCode::URI_TOO_LONG, 414, 'URI Too Long', '414 URI Too Long', true, false, false, false, true, false],
            [StatusCode::UNSUPPORTED_MEDIA_TYPE, 415, 'Unsupported Media Type', '415 Unsupported Media Type', true, false, false, false, true, false],
            [StatusCode::RANGE_NOT_SATISFIABLE, 416, 'Range Not Satisfiable', '416 Range Not Satisfiable', true, false, false, false, true, false],
            [StatusCode::EXPECTATION_FAILED, 417, 'Expectation Failed', '417 Expectation Failed', true, false, false, false, true, false],
            [StatusCode::MISDIRECTED_REQUEST, 421, 'Misdirected Request', '421 Misdirected Request', true, false, false, false, true, false],
            [StatusCode::UNPROCESSABLE_ENTITY, 422, 'Unprocessable Entity', '422 Unprocessable Entity', true, false, false, false, true, false],
            [StatusCode::LOCKED, 423, 'Locked', '423 Locked', true, false, false, false, true, false],
            [StatusCode::FAILED_DEPENDENCY, 424, 'Failed Dependency', '424 Failed Dependency', true, false, false, false, true, false],
            [StatusCode::UPGRADE_REQUIRED, 426, 'Upgrade Required', '426 Upgrade Required', true, false, false, false, true, false],
            [StatusCode::PRECONDITION_REQUIRED, 428, 'Precondition Required', '428 Precondition Required', true, false, false, false, true, false],
            [StatusCode::TOO_MANY_REQUESTS, 429, 'Too Many Requests', '429 Too Many Requests', true, false, false, false, true, false],
            [StatusCode::REQUEST_HEADER_FIELDS_TOO_LARGE, 431, 'Request Header Fields Too Large', '431 Request Header Fields Too Large', true, false, false, false, true, false],
            [StatusCode::INTERNAL_SERVER_ERROR, 500, 'Internal Server Error', '500 Internal Server Error', true, false, false, false, false, true],
            [StatusCode::NOT_IMPLEMENTED, 501, 'Not Implemented', '501 Not Implemented', true, false, false, false, false, true],
            [StatusCode::BAD_GATEWAY, 502, 'Bad Gateway', '502 Bad Gateway', true, false, false, false, false, true],
            [StatusCode::SERVICE_UNAVAILABLE, 503, 'Service Unavailable', '503 Service Unavailable', true, false, false, false, false, true],
            [StatusCode::GATEWAY_TIMEOUT, 504, 'Gateway Timeout', '504 Gateway Timeout', true, false, false, false, false, true],
            [StatusCode::HTTP_VERSION_NOT_SUPPORTED, 505, 'HTTP Version Not Supported', '505 HTTP Version Not Supported', true, false, false, false, false, true],
            [StatusCode::VARIANT_ALSO_NEGOTIATES, 506, 'Variant Also Negotiates', '506 Variant Also Negotiates', true, false, false, false, false, true],
            [StatusCode::INSUFFICIENT_STORAGE, 507, 'Insufficient Storage', '507 Insufficient Storage', true, false, false, false, false, true],
            [StatusCode::LOOP_DETECTED, 508, 'Loop Detected', '508 Loop Detected', true, false, false, false, false, true],
            [StatusCode::NOT_EXTENDED, 510, 'Not Extended', '510 Not Extended', true, false, false, false, false, true],
            [StatusCode::NETWORK_AUTHENTICATION_REQUIRED, 511, 'Network Authentication Required', '511 Network Authentication Required', true, false, false, false, false, true],
        ];
    }
}
