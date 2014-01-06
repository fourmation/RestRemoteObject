<?php

namespace RestRemoteObject\Client\Rest\Debug\Verbosity;

class Verbosity
{
    const TRACE_DISABLED = 0;
    const TRACE_REQUEST_URI = 1;
    const TRACE_REQUEST_HTTP_METHOD = 2;
    const TRACE_REQUEST_HTTP_PARAMS = 4;
    const TRACE_REQUEST_HTTP_STATUS_CODE = 8;
    const TRACE_REQUEST_HTTP_RESPONSE = 16;

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
        return (bool) ($this->level & self::TRACE_REQUEST_URI);
    }

    public function hasHttpMethodTrace()
    {
        return (bool) ($this->level & self::TRACE_REQUEST_HTTP_METHOD);
    }

    public function hasHttpParamsTrace()
    {
        return (bool) ($this->level & self::TRACE_REQUEST_HTTP_PARAMS);
    }

    public function hasHttpStatusCodeTrace()
    {
        return (bool) ($this->level & self::TRACE_REQUEST_HTTP_STATUS_CODE);
    }

    public function hasHttpResponseTrace()
    {
        return (bool) ($this->level & self::TRACE_REQUEST_HTTP_RESPONSE);
    }
}
