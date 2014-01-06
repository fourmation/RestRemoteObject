<?php

namespace RestRemoteObject\Client\Rest\Versioning;

use RestRemoteObject\Client\Rest\Context;

class UriVersioningStrategy implements VersioningStrategyInterface
{
    /**
     * @var string $version
     */
    protected $version;

    /**
     * @var string $baseUrl
     */
    protected $baseUrl;

    /**
     * @param string      $version
     * @param null|string $baseUrl
     */
    public function __construct($version, $baseUrl = null)
    {
        $this->version = $version;
        if ($baseUrl) {
            $this->baseUrl = '/' . trim($baseUrl, '\/');
        }
    }

    /**
     * Version the request
     * @param  Context $context
     * @return void
     */
    public function version(Context $context)
    {
        $uri = $context->getRequest()->getUri();
        if (!$this->baseUrl) {
            $uri->setPath('/' . $this->version . $uri->getPath());
        } else {
            $path = $uri->getPath();
            $path = str_replace($this->baseUrl, $this->baseUrl . '/' . $this->version, $path);
            $uri->setPath($path);
        }
    }
}
