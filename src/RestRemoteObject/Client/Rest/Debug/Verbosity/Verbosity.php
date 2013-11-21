<?php

namespace RestRemoteObject\Client\Rest\Debug\Verbosity;

class Verbosity
{
    CONST TRACE_DISABLED = 0;
    CONST TRACE_REQUEST_URI = 1;
    CONST TRACE_REQUEST_HTTP_METHOD = 2;
    CONST TRACE_REQUEST_HTTP_PARAMS = 4;
    CONST TRACE_REQUEST_HTTP_STATUS_CODE = 8;
    CONST TRACE_REQUEST_HTTP_RESPONSE = 16;

    /**
     * @var int
     */
    protected $level;

    public function __construct($level = self::TRACE_DISABLED)
    {
        $this->level = $level;
    }

    public function hasUriTrace()
    {
        return ($this->level & self::TRACE_REQUEST_URI) == self::TRACE_REQUEST_URI;
    }

    public function hasHttpMethodTrace()
    {
        return ($this->level & self::TRACE_REQUEST_HTTP_METHOD) == self::TRACE_REQUEST_HTTP_METHOD;
    }

    public function hasHttpParamsTrace()
    {
        return ($this->level & self::TRACE_REQUEST_HTTP_PARAMS) == self::TRACE_REQUEST_HTTP_PARAMS;
    }

    public function hasHttpStatusCodeTrace()
    {
        return ($this->level & self::TRACE_REQUEST_HTTP_STATUS_CODE) == self::TRACE_REQUEST_HTTP_STATUS_CODE;
    }

    public function hasHttpResponseTrace()
    {
        return ($this->level & self::TRACE_REQUEST_HTTP_RESPONSE) == self::TRACE_REQUEST_HTTP_RESPONSE;
    }
}