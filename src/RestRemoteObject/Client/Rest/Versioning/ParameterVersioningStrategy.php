<?php

namespace RestRemoteObject\Client\Rest\Versioning;

use RestRemoteObject\Client\Rest\Context;

class ParameterVersioningStrategy implements VersioningStrategyInterface
{
    /**
     * @var string $version
     */
    protected $version;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Version the request
     * @param  Context $context
     * @return void
     */
    public function version(Context $context)
    {
        $uri = $context->getRequest()->getUri();
        $query = $uri->getQueryAsArray();

        $query['v'] = $this->version;
        $uri->setQuery($query);
    }
}
