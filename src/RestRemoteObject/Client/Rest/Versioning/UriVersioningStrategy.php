<?php

namespace RestRemoteObject\Client\Rest\Versioning;

use Zend\Http\Request;

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
     * @param string $version
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
     * @param Request $request
     * @return void
     */
    public function version(Request $request)
    {
        $uri = $request->getUri();
        if (!$this->baseUrl) {
            $uri->setPath('/' . $this->version . $uri->getPath());
        } else {
            $path = $uri->getPath();
            $path = str_replace($this->baseUrl, $this->baseUrl . '/' . $this->version, $path);
            $uri->setPath($path);
        }
    }
}