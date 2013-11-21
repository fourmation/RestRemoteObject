<?php

namespace RestRemoteObject\Client\Rest\Debug\Verbosity\Cli;

use RestRemoteObject\Client\Rest\Debug\Verbosity\Verbosity as BaseVerbosity;

class Verbosity extends BaseVerbosity
{
    /**
     * @var int
     */
    protected $level;

    public function __construct($level)
    {
        parent::__construct(PHP_SAPI == 'cli' ? $level : 0);
    }
}