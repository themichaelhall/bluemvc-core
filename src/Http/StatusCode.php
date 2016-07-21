<?php

namespace BlueMvc\Core\Http;

use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;

/**
 * Class representing a http status code.
 */
class StatusCode implements StatusCodeInterface
{
    /**
     * Continue status code.
     */
    const CONTINUE_ = 100;

    /**
     * Switching Protocols status code.
     */
    const SWITCHING_PROTOCOLS = 101;

    /**
     * Processing status code.
     */
    const PROCESSING = 102;

    /**
     * OK status code.
     */
    const OK = 200;

    /**
     * Created status code.
     */
    const CREATED = 201;

    /**
     * Accepted status code.
     */
    const ACCEPTED = 202;

    /**
     * Non-Authoritative Information status code.
     */
    const NON_AUTHORITATIVE_INFORMATION = 203;

    /**
     * No Content status code.
     */
    const NO_CONTENT = 204;

    /**
     * Reset Content status code.
     */
    const RESET_CONTENT = 205;

    /**
     * Partial Content status code.
     */
    const PARTIAL_CONTENT = 206;

    /**
     * Multi Status status code.
     */
    const MULTI_STATUS = 207;

    /**
     * Already Reported status code.
     */
    const ALREADY_REPORTED = 208;

    /**
     * IM Used status code.
     */
    const IM_USED = 226;

    /**
     * Multiple Choices status code.
     */
    const MULTIPLE_CHOICES = 300;

    /**
     * Moved Permanently status code.
     */
    const MOVED_PERMANENTLY = 301;

    /**
     * Found status code.
     */
    const FOUND = 302;

    /**
     * See Other status code.
     */
    const SEE_OTHER = 303;

    /**
     * Not Modified status code.
     */
    const NOT_MODIFIED = 304;

    /**
     * Use Proxy status code.
     */
    const USE_PROXY = 305;

    /**
     * Temporary Redirect status code.
     */
    const TEMPORARY_REDIRECT = 307;

    /**
     * Permanent Redirect status code.
     */
    const PERMANENT_REDIRECT = 308;

    /**
     * Bad Request status code.
     */
    const BAD_REQUEST = 400;

    /**
     * Unauthorized status code.
     */
    const UNAUTHORIZED = 401;

    /**
     * Payment Required status code.
     */
    const PAYMENT_REQUIRED = 402;

    /**
     * Forbidden status code.
     */
    const FORBIDDEN = 403;

    /**
     * Not Found status code.
     */
    const NOT_FOUND = 404;

    /**
     * Method Not Allowed status code.
     */
    const METHOD_NOT_ALLOWED = 405;

    /**
     * Not Acceptable status code.
     */
    const NOT_ACCEPTABLE = 406;

    /**
     * Proxy Authentication Required status code.
     */
    const PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * Request Timeout status code.
     */
    const REQUEST_TIMEOUT = 408;

    /**
     * Conflict status code.
     */
    const CONFLICT = 409;

    /**
     * Gone status code.
     */
    const GONE = 410;

    /**
     * Length Required status code.
     */
    const LENGTH_REQUIRED = 411;

    /**
     * Precondition Failed status code.
     */
    const PRECONDITION_FAILED = 412;

    /**
     * Payload Too Large status code.
     */
    const PAYLOAD_TOO_LARGE = 413;

    /**
     * URI Too Long status code.
     */
    const URI_TOO_LONG = 414;

    /**
     * Unsupported Media Type status code.
     */
    const UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * Range Not Satisfiable status code.
     */
    const RANGE_NOT_SATISFIABLE = 416;

    /**
     * Expectation Failed status code.
     */
    const EXPECTATION_FAILED = 417;

    /**
     * Misdirected Request status code.
     */
    const MISDIRECTED_REQUEST = 421;

    /**
     * Unprocessable Entity status code.
     */
    const UNPROCESSABLE_ENTITY = 422;

    /**
     * Locked status code.
     */
    const LOCKED = 423;

    /**
     * Failed Dependency status code.
     */
    const FAILED_DEPENDENCY = 424;

    /**
     * Upgrade Required status code.
     */
    const UPGRADE_REQUIRED = 426;

    /**
     * Precondition Required status code.
     */
    const PRECONDITION_REQUIRED = 428;

    /**
     * Too Many Requests status code.
     */
    const TOO_MANY_REQUESTS = 429;

    /**
     * Request Header Fields Too Large status code.
     */
    const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    /**
     * Internal Server Error status code.
     */
    const INTERNAL_SERVER_ERROR = 500;

    /**
     * Not Implemented status code.
     */
    const NOT_IMPLEMENTED = 501;

    /**
     * Bad Gateway status code.
     */
    const BAD_GATEWAY = 502;

    /**
     * Service Unavailable status code.
     */
    const SERVICE_UNAVAILABLE = 503;

    /**
     * Gateway Timeout status code.
     */
    const GATEWAY_TIMEOUT = 504;

    /**
     * HTTP Version Not Supported status code.
     */
    const HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * Variant Also Negotiates status code.
     */
    const VARIANT_ALSO_NEGOTIATES = 506;

    /**
     * Insufficient Storage status code.
     */
    const INSUFFICIENT_STORAGE = 507;

    /**
     * Loop Detected status code.
     */
    const LOOP_DETECTED = 508;

    /**
     * Not Extended status code.
     */
    const NOT_EXTENDED = 510;

    /**
     * Network Authentication Required status code.
     */
    const NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * Constructs a status code.
     *
     * @param int $code The code.
     */
    public function __construct($code)
    {
        assert(is_int($code));

        $this->myCode = $code;
        $this->myDescription = self::$myDescriptions[$code];
        // fixme: handle invalid code
    }

    /**
     * @return int The code.
     */
    public function getCode()
    {
        return $this->myCode;
    }

    /**
     * @return string The description.
     */
    public function getDescription()
    {
        return $this->myDescription;
    }

    /**
     * @return string The status code as a string.
     */
    public function __toString()
    {
        return $this->myCode . ' ' . $this->myDescription;
    }

    /**
     * @var int My code.
     */
    private $myCode;

    /**
     * @var string My description.
     */
    private $myDescription;

    /**
     * @var array My descriptions.
     */
    private static $myDescriptions = [

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
