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
        return (bool)($this->level & self::TRACE_REQUEST_URI);
    }

    public function hasHttpMethodTrace()
    {
        return (bool)($this->level & self::TRACE_REQUEST_HTTP_METHOD);
    }

    public function hasHttpParamsTrace()
    {
        return (bool)($this->level & self::TRACE_REQUEST_HTTP_PARAMS);
    }

    public function hasHttpStatusCodeTrace()
    {
        return (bool)($this->level & self::TRACE_REQUEST_HTTP_STATUS_CODE);
    }

    public function hasHttpResponseTrace()
    {
        return (bool)($this->level & self::TRACE_REQUEST_HTTP_RESPONSE);
    }
}