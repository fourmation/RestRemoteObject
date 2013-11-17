<?php

namespace RestRemoteObject\Client\Rest\Versioning;

use RestRemoteObject\Client\Rest\Context;

class HeaderVersioningStrategy implements VersioningStrategyInterface
{
    /**
     * @var string $version
     */
    protected $version;

    /**
     * @var string $header
     */
    protected $header;

    /**
     * @param string $version
     * @param string $header
     */
    public function __construct($version, $header = 'Rest-Version')
    {
        $this->version = $version;
        $this->header = $header;
    }

    /**
     * Version the request
     * @param Context $context
     * @return void
     */
    public function version(Context $context)
    {
        $context->getRequest()->getHeaders()->addHeaderLine($this->header . ': ' . $this->version);
    }
}