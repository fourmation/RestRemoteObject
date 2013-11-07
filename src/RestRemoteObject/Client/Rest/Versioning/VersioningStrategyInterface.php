<?php

namespace RestRemoteObject\Client\Rest\Versioning;

use Zend\Http\Request;

interface VersioningStrategyInterface
{
    /**
     * Version the request
     * @param Request $request
     * @return void
     */
    public function version(Request $request);
}