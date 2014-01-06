<?php

namespace RestRemoteObject\Client\Rest\Debug\Verbosity\Cli;

use RestRemoteObject\Client\Rest\Debug\Verbosity\Verbosity;

class ResourceVerbosity extends Verbosity
{
    /**
     * @var int
     */
    protected $level;

    public function __construct()
    {
        parent::__construct(Verbosity::TRACE_REQUEST_URI | Verbosity::TRACE_REQUEST_HTTP_METHOD);
    }
}
