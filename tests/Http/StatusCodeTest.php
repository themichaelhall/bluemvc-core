<?php

use BlueMvc\Core\Http\StatusCode;

/**
 * Test StatusCode class.
 */
class StatusCodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getCode method.
     *
     * @param int    $constant    The code as a constant from StatusCode class.
     * @param int    $code        The code.
     * @param string $description The description.
     * @param string $asString    The status code as a string.
     *
     * @dataProvider statusCodeProvider
     */
    public function testGetCode(
        $constant,
        $code,
        /** @noinspection PhpUnusedParameterInspection */
        $description,
        /** @noinspection PhpUnusedParameterInspection */
        $asString
    ) {
        $statusCode = new StatusCode($constant);

        $this->assertSame($code, $statusCode->getCode());
    }

    /**
     * Test getDescription method.
     *
     * @param int    $constant    The code as a constant from StatusCode class.
     * @param int    $code        The code.
     * @param string $description The description.
     * @param string $asString    The status code as a string.
     *
     * @dataProvider statusCodeProvider
     */
    public function testGetDescription(
        $constant,
        /** @noinspection PhpUnusedParameterInspection */
        $code,
        $description,
        /** @noinspection PhpUnusedParameterInspection */
        $asString
    ) {
        $statusCode = new StatusCode($constant);

        $this->assertSame($description, $statusCode->getDescription());
    }

    /**
     * Test __toString method.
     *
     * @param int    $constant    The code as a constant from StatusCode class.
     * @param int    $code        The code.
     * @param string $description The description.
     * @param string $asString    The status code as a string.
     *
     * @dataProvider statusCodeProvider
     */
    public function testToString(
        $constant,
        /** @noinspection PhpUnusedParameterInspection */
        $code,
        /** @noinspection PhpUnusedParameterInspection */
        $description,
        $asString
    ) {
        $statusCode = new StatusCode($constant);

        $this->assertSame($asString, $statusCode->__toString());
    }

    /**
     * Test that an invalid status code is invalid.
     *
     * @expectedException \BlueMvc\Core\Exceptions\Http\InvalidStatusCodeException
     * @expectedExceptionMessage Status code 99 is invalid.
     */
    public function testInvalidStatusCodeIsInvalid()
    {
        new StatusCode(99);
    }

    /**
     * Data provider for status code tests.
     *
     * @return array The data.
     */
    public function statusCodeProvider()
    {
        return [
            [StatusCode::CONTINUE_, 100, 'Continue', '100 Continue'],
            [StatusCode::SWITCHING_PROTOCOLS, 101, 'Switching Protocols', '101 Switching Protocols'],
            [StatusCode::PROCESSING, 102, 'Processing', '102 Processing'],
            [StatusCode::OK, 200, 'OK', '200 OK'],
            [StatusCode::CREATED, 201, 'Created', '201 Created'],
            [StatusCode::ACCEPTED, 202, 'Accepted', '202 Accepted'],
            [StatusCode::NON_AUTHORITATIVE_INFORMATION, 203, 'Non-Authoritative Information', '203 Non-Authoritative Information'],
            [StatusCode::NO_CONTENT, 204, 'No Content', '204 No Content'],
            [StatusCode::RESET_CONTENT, 205, 'Reset Content', '205 Reset Content'],
            [StatusCode::PARTIAL_CONTENT, 206, 'Partial Content', '206 Partial Content'],
            [StatusCode::MULTI_STATUS, 207, 'Multi-Status', '207 Multi-Status'],
            [StatusCode::ALREADY_REPORTED, 208, 'Already Reported', '208 Already Reported'],
            [StatusCode::IM_USED, 226, 'IM Used', '226 IM Used'],
            [StatusCode::MULTIPLE_CHOICES, 300, 'Multiple Choices', '300 Multiple Choices'],
            [StatusCode::MOVED_PERMANENTLY, 301, 'Moved Permanently', '301 Moved Permanently'],
            [StatusCode::FOUND, 302, 'Found', '302 Found'],
            [StatusCode::SEE_OTHER, 303, 'See Other', '303 See Other'],
            [StatusCode::NOT_MODIFIED, 304, 'Not Modified', '304 Not Modified'],
            [StatusCode::USE_PROXY, 305, 'Use Proxy', '305 Use Proxy'],
            [StatusCode::TEMPORARY_REDIRECT, 307, 'Temporary Redirect', '307 Temporary Redirect'],
            [StatusCode::PERMANENT_REDIRECT, 308, 'Permanent Redirect', '308 Permanent Redirect'],
            [StatusCode::BAD_REQUEST, 400, 'Bad Request', '400 Bad Request'],
            [StatusCode::UNAUTHORIZED, 401, 'Unauthorized', '401 Unauthorized'],
            [StatusCode::PAYMENT_REQUIRED, 402, 'Payment Required', '402 Payment Required'],
            [StatusCode::FORBIDDEN, 403, 'Forbidden', '403 Forbidden'],
            [StatusCode::NOT_FOUND, 404, 'Not Found', '404 Not Found'],
            [StatusCode::METHOD_NOT_ALLOWED, 405, 'Method Not Allowed', '405 Method Not Allowed'],
            [StatusCode::NOT_ACCEPTABLE, 406, 'Not Acceptable', '406 Not Acceptable'],
            [StatusCode::PROXY_AUTHENTICATION_REQUIRED, 407, 'Proxy Authentication Required', '407 Proxy Authentication Required'],
            [StatusCode::REQUEST_TIMEOUT, 408, 'Request Timeout', '408 Request Timeout'],
            [StatusCode::CONFLICT, 409, 'Conflict', '409 Conflict'],
            [StatusCode::GONE, 410, 'Gone', '410 Gone'],
            [StatusCode::LENGTH_REQUIRED, 411, 'Length Required', '411 Length Required'],
            [StatusCode::PRECONDITION_FAILED, 412, 'Precondition Failed', '412 Precondition Failed'],
            [StatusCode::PAYLOAD_TOO_LARGE, 413, 'Payload Too Large', '413 Payload Too Large'],
            [StatusCode::URI_TOO_LONG, 414, 'URI Too Long', '414 URI Too Long'],
            [StatusCode::UNSUPPORTED_MEDIA_TYPE, 415, 'Unsupported Media Type', '415 Unsupported Media Type'],
            [StatusCode::RANGE_NOT_SATISFIABLE, 416, 'Range Not Satisfiable', '416 Range Not Satisfiable'],
            [StatusCode::EXPECTATION_FAILED, 417, 'Expectation Failed', '417 Expectation Failed'],
            [StatusCode::MISDIRECTED_REQUEST, 421, 'Misdirected Request', '421 Misdirected Request'],
            [StatusCode::UNPROCESSABLE_ENTITY, 422, 'Unprocessable Entity', '422 Unprocessable Entity'],
            [StatusCode::LOCKED, 423, 'Locked', '423 Locked'],
            [StatusCode::FAILED_DEPENDENCY, 424, 'Failed Dependency', '424 Failed Dependency'],
            [StatusCode::UPGRADE_REQUIRED, 426, 'Upgrade Required', '426 Upgrade Required'],
            [StatusCode::PRECONDITION_REQUIRED, 428, 'Precondition Required', '428 Precondition Required'],
            [StatusCode::TOO_MANY_REQUESTS, 429, 'Too Many Requests', '429 Too Many Requests'],
            [StatusCode::REQUEST_HEADER_FIELDS_TOO_LARGE, 431, 'Request Header Fields Too Large', '431 Request Header Fields Too Large'],
            [StatusCode::INTERNAL_SERVER_ERROR, 500, 'Internal Server Error', '500 Internal Server Error'],
            [StatusCode::NOT_IMPLEMENTED, 501, 'Not Implemented', '501 Not Implemented'],
            [StatusCode::BAD_GATEWAY, 502, 'Bad Gateway', '502 Bad Gateway'],
            [StatusCode::SERVICE_UNAVAILABLE, 503, 'Service Unavailable', '503 Service Unavailable'],
            [StatusCode::GATEWAY_TIMEOUT, 504, 'Gateway Timeout', '504 Gateway Timeout'],
            [StatusCode::HTTP_VERSION_NOT_SUPPORTED, 505, 'HTTP Version Not Supported', '505 HTTP Version Not Supported'],
            [StatusCode::VARIANT_ALSO_NEGOTIATES, 506, 'Variant Also Negotiates', '506 Variant Also Negotiates'],
            [StatusCode::INSUFFICIENT_STORAGE, 507, 'Insufficient Storage', '507 Insufficient Storage'],
            [StatusCode::LOOP_DETECTED, 508, 'Loop Detected', '508 Loop Detected'],
            [StatusCode::NOT_EXTENDED, 510, 'Not Extended', '510 Not Extended'],
            [StatusCode::NETWORK_AUTHENTICATION_REQUIRED, 511, 'Network Authentication Required', '511 Network Authentication Required'],
        ];
    }
}
