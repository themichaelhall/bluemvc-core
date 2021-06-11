<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Http;

use BlueMvc\Core\Exceptions\Http\InvalidStatusCodeException;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;

/**
 * Class representing a http status code.
 *
 * @since 1.0.0
 */
class StatusCode implements StatusCodeInterface
{
    /**
     * Continue status code.
     *
     * @since 1.0.0
     */
    public const CONTINUE_ = 100;

    /**
     * Switching Protocols status code.
     *
     * @since 1.0.0
     */
    public const SWITCHING_PROTOCOLS = 101;

    /**
     * Processing status code.
     *
     * @since 1.0.0
     */
    public const PROCESSING = 102;

    /**
     * OK status code.
     *
     * @since 1.0.0
     */
    public const OK = 200;

    /**
     * Created status code.
     *
     * @since 1.0.0
     */
    public const CREATED = 201;

    /**
     * Accepted status code.
     *
     * @since 1.0.0
     */
    public const ACCEPTED = 202;

    /**
     * Non-Authoritative Information status code.
     *
     * @since 1.0.0
     */
    public const NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * No Content status code.
     *
     * @since 1.0.0
     */
    public const NO_CONTENT = 204;

    /**
     * Reset Content status code.
     *
     * @since 1.0.0
     */
    public const RESET_CONTENT = 205;

    /**
     * Partial Content status code.
     *
     * @since 1.0.0
     */
    public const PARTIAL_CONTENT = 206;

    /**
     * Multi Status status code.
     *
     * @since 1.0.0
     */
    public const MULTI_STATUS = 207;

    /**
     * Already Reported status code.
     *
     * @since 1.0.0
     */
    public const ALREADY_REPORTED = 208;

    /**
     * IM Used status code.
     *
     * @since 1.0.0
     */
    public const IM_USED = 226;

    /**
     * Multiple Choices status code.
     *
     * @since 1.0.0
     */
    public const MULTIPLE_CHOICES = 300;

    /**
     * Moved Permanently status code.
     *
     * @since 1.0.0
     */
    public const MOVED_PERMANENTLY = 301;

    /**
     * Found status code.
     *
     * @since 1.0.0
     */
    public const FOUND = 302;

    /**
     * See Other status code.
     *
     * @since 1.0.0
     */
    public const SEE_OTHER = 303;

    /**
     * Not Modified status code.
     *
     * @since 1.0.0
     */
    public const NOT_MODIFIED = 304;

    /**
     * Use Proxy status code.
     *
     * @since 1.0.0
     */
    public const USE_PROXY = 305;

    /**
     * Temporary Redirect status code.
     *
     * @since 1.0.0
     */
    public const TEMPORARY_REDIRECT = 307;

    /**
     * Permanent Redirect status code.
     *
     * @since 1.0.0
     */
    public const PERMANENT_REDIRECT = 308;

    /**
     * Bad Request status code.
     *
     * @since 1.0.0
     */
    public const BAD_REQUEST = 400;

    /**
     * Unauthorized status code.
     *
     * @since 1.0.0
     */
    public const UNAUTHORIZED = 401;

    /**
     * Payment Required status code.
     *
     * @since 1.0.0
     */
    public const PAYMENT_REQUIRED = 402;

    /**
     * Forbidden status code.
     *
     * @since 1.0.0
     */
    public const FORBIDDEN = 403;

    /**
     * Not Found status code.
     *
     * @since 1.0.0
     */
    public const NOT_FOUND = 404;

    /**
     * Method Not Allowed status code.
     *
     * @since 1.0.0
     */
    public const METHOD_NOT_ALLOWED = 405;

    /**
     * Not Acceptable status code.
     *
     * @since 1.0.0
     */
    public const NOT_ACCEPTABLE = 406;

    /**
     * Proxy Authentication Required status code.
     *
     * @since 1.0.0
     */
    public const PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * Request Timeout status code.
     *
     * @since 1.0.0
     */
    public const REQUEST_TIMEOUT = 408;

    /**
     * Conflict status code.
     *
     * @since 1.0.0
     */
    public const CONFLICT = 409;

    /**
     * Gone status code.
     *
     * @since 1.0.0
     */
    public const GONE = 410;

    /**
     * Length Required status code.
     *
     * @since 1.0.0
     */
    public const LENGTH_REQUIRED = 411;

    /**
     * Precondition Failed status code.
     *
     * @since 1.0.0
     */
    public const PRECONDITION_FAILED = 412;

    /**
     * Payload Too Large status code.
     *
     * @since 1.0.0
     */
    public const PAYLOAD_TOO_LARGE = 413;

    /**
     * URI Too Long status code.
     *
     * @since 1.0.0
     */
    public const URI_TOO_LONG = 414;

    /**
     * Unsupported Media Type status code.
     *
     * @since 1.0.0
     */
    public const UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * Range Not Satisfiable status code.
     *
     * @since 1.0.0
     */
    public const RANGE_NOT_SATISFIABLE = 416;

    /**
     * Expectation Failed status code.
     *
     * @since 1.0.0
     */
    public const EXPECTATION_FAILED = 417;

    /**
     * Misdirected Request status code.
     *
     * @since 1.0.0
     */
    public const MISDIRECTED_REQUEST = 421;

    /**
     * Unprocessable Entity status code.
     *
     * @since 1.0.0
     */
    public const UNPROCESSABLE_ENTITY = 422;

    /**
     * Locked status code.
     *
     * @since 1.0.0
     */
    public const LOCKED = 423;

    /**
     * Failed Dependency status code.
     *
     * @since 1.0.0
     */
    public const FAILED_DEPENDENCY = 424;

    /**
     * Upgrade Required status code.
     *
     * @since 1.0.0
     */
    public const UPGRADE_REQUIRED = 426;

    /**
     * Precondition Required status code.
     *
     * @since 1.0.0
     */
    public const PRECONDITION_REQUIRED = 428;

    /**
     * Too Many Requests status code.
     *
     * @since 1.0.0
     */
    public const TOO_MANY_REQUESTS = 429;

    /**
     * Request Header Fields Too Large status code.
     *
     * @since 1.0.0
     */
    public const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    /**
     * Internal Server Error status code.
     *
     * @since 1.0.0
     */
    public const INTERNAL_SERVER_ERROR = 500;

    /**
     * Not Implemented status code.
     *
     * @since 1.0.0
     */
    public const NOT_IMPLEMENTED = 501;

    /**
     * Bad Gateway status code.
     *
     * @since 1.0.0
     */
    public const BAD_GATEWAY = 502;

    /**
     * Service Unavailable status code.
     *
     * @since 1.0.0
     */
    public const SERVICE_UNAVAILABLE = 503;

    /**
     * Gateway Timeout status code.
     *
     * @since 1.0.0
     */
    public const GATEWAY_TIMEOUT = 504;

    /**
     * HTTP Version Not Supported status code.
     *
     * @since 1.0.0
     */
    public const HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * Variant Also Negotiates status code.
     *
     * @since 1.0.0
     */
    public const VARIANT_ALSO_NEGOTIATES = 506;

    /**
     * Insufficient Storage status code.
     *
     * @since 1.0.0
     */
    public const INSUFFICIENT_STORAGE = 507;

    /**
     * Loop Detected status code.
     *
     * @since 1.0.0
     */
    public const LOOP_DETECTED = 508;

    /**
     * Not Extended status code.
     *
     * @since 1.0.0
     */
    public const NOT_EXTENDED = 510;

    /**
     * Network Authentication Required status code.
     *
     * @since 1.0.0
     */
    public const NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * Constructs a status code.
     *
     * @since 1.0.0
     *
     * @param int $code The code.
     *
     * @throws InvalidStatusCodeException If the code is invalid.
     */
    public function __construct(int $code)
    {
        if (!isset(self::$descriptions[$code])) {
            throw new InvalidStatusCodeException('Status code ' . $code . ' is invalid.');
        }

        $this->code = $code;
        $this->description = self::$descriptions[$code];
    }

    /**
     * Returns the code.
     *
     * @since 1.0.0
     *
     * @return int The code.
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Returns the description.
     *
     * @since 1.0.0
     *
     * @return string The description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns true if this is a 4xx client error code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 4xx client error code, false otherwise.
     */
    public function isClientError(): bool
    {
        return $this->isStatusCodeClass(400);
    }

    /**
     * Returns true if this is a 4xx or 5xx error code, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a 4xx or 5xx error code, false otherwise.
     */
    public function isError(): bool
    {
        return $this->isClientError() || $this->isServerError();
    }

    /**
     * Returns true if this is a 1xx informational code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 1xx informational code, false otherwise.
     */
    public function isInformational(): bool
    {
        return $this->isStatusCodeClass(100);
    }

    /**
     * Returns true if this is a 3xx redirection code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 3xx redirection code, false otherwise.
     */
    public function isRedirection(): bool
    {
        return $this->isStatusCodeClass(300);
    }

    /**
     * Returns true if this is a 5xx server error code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 5xx server error code, false otherwise.
     */
    public function isServerError(): bool
    {
        return $this->isStatusCodeClass(500);
    }

    /**
     * Returns true if this is a 2xx successful code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 2xx successful code, false otherwise.
     */
    public function isSuccessful(): bool
    {
        return $this->isStatusCodeClass(200);
    }

    /**
     * Returns the status code as as string.
     *
     * @since 1.0.0
     *
     * @return string The status code as a string.
     */
    public function __toString(): string
    {
        return $this->code . ' ' . $this->description;
    }

    /**
     * Returns true of the status code belongs to the given status code class (100/200/300/400/500), false otherwise.
     *
     * @param int $statusCodeClass The status code class.
     *
     * @return bool True of the status code belongs to the given status code class, false otherwise.
     */
    private function isStatusCodeClass(int $statusCodeClass): bool
    {
        return $this->code >= $statusCodeClass && $this->code <= $statusCodeClass + 99;
    }

    /**
     * @var int My code.
     */
    private $code;

    /**
     * @var string My description.
     */
    private $description;

    /**
     * @var array My descriptions.
     */
    private static $descriptions = [
        self::CONTINUE_                       => 'Continue',
        self::SWITCHING_PROTOCOLS             => 'Switching Protocols',
        self::PROCESSING                      => 'Processing',
        self::OK                              => 'OK',
        self::CREATED                         => 'Created',
        self::ACCEPTED                        => 'Accepted',
        self::NON_AUTHORITATIVE_INFORMATION   => 'Non-Authoritative Information',
        self::NO_CONTENT                      => 'No Content',
        self::RESET_CONTENT                   => 'Reset Content',
        self::PARTIAL_CONTENT                 => 'Partial Content',
        self::MULTI_STATUS                    => 'Multi-Status',
        self::ALREADY_REPORTED                => 'Already Reported',
        self::IM_USED                         => 'IM Used',
        self::MULTIPLE_CHOICES                => 'Multiple Choices',
        self::MOVED_PERMANENTLY               => 'Moved Permanently',
        self::FOUND                           => 'Found',
        self::SEE_OTHER                       => 'See Other',
        self::NOT_MODIFIED                    => 'Not Modified',
        self::USE_PROXY                       => 'Use Proxy',
        self::TEMPORARY_REDIRECT              => 'Temporary Redirect',
        self::PERMANENT_REDIRECT              => 'Permanent Redirect',
        self::BAD_REQUEST                     => 'Bad Request',
        self::UNAUTHORIZED                    => 'Unauthorized',
        self::PAYMENT_REQUIRED                => 'Payment Required',
        self::FORBIDDEN                       => 'Forbidden',
        self::NOT_FOUND                       => 'Not Found',
        self::METHOD_NOT_ALLOWED              => 'Method Not Allowed',
        self::NOT_ACCEPTABLE                  => 'Not Acceptable',
        self::PROXY_AUTHENTICATION_REQUIRED   => 'Proxy Authentication Required',
        self::REQUEST_TIMEOUT                 => 'Request Timeout',
        self::CONFLICT                        => 'Conflict',
        self::GONE                            => 'Gone',
        self::LENGTH_REQUIRED                 => 'Length Required',
        self::PRECONDITION_FAILED             => 'Precondition Failed',
        self::PAYLOAD_TOO_LARGE               => 'Payload Too Large',
        self::URI_TOO_LONG                    => 'URI Too Long',
        self::UNSUPPORTED_MEDIA_TYPE          => 'Unsupported Media Type',
        self::RANGE_NOT_SATISFIABLE           => 'Range Not Satisfiable',
        self::EXPECTATION_FAILED              => 'Expectation Failed',
        self::MISDIRECTED_REQUEST             => 'Misdirected Request',
        self::UNPROCESSABLE_ENTITY            => 'Unprocessable Entity',
        self::LOCKED                          => 'Locked',
        self::FAILED_DEPENDENCY               => 'Failed Dependency',
        self::UPGRADE_REQUIRED                => 'Upgrade Required',
        self::PRECONDITION_REQUIRED           => 'Precondition Required',
        self::TOO_MANY_REQUESTS               => 'Too Many Requests',
        self::REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
        self::INTERNAL_SERVER_ERROR           => 'Internal Server Error',
        self::NOT_IMPLEMENTED                 => 'Not Implemented',
        self::BAD_GATEWAY                     => 'Bad Gateway',
        self::SERVICE_UNAVAILABLE             => 'Service Unavailable',
        self::GATEWAY_TIMEOUT                 => 'Gateway Timeout',
        self::HTTP_VERSION_NOT_SUPPORTED      => 'HTTP Version Not Supported',
        self::VARIANT_ALSO_NEGOTIATES         => 'Variant Also Negotiates',
        self::INSUFFICIENT_STORAGE            => 'Insufficient Storage',
        self::LOOP_DETECTED                   => 'Loop Detected',
        self::NOT_EXTENDED                    => 'Not Extended',
        self::NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required',
    ];
}
