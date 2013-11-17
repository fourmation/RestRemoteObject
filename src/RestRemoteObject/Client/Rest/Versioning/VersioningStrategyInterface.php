<?php

namespace RestRemoteObject\Client\Rest\Versioning;

use RestRemoteObject\Client\Rest\Context;

interface VersioningStrategyInterface
{
    /**
     * Version the request
     * @param Context $context
     * @return void
     */
    public function version(Context $context);
}