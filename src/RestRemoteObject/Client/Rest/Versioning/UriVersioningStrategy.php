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
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Version the request
     * @param Request $request
     * @return void
     */
    public function version(Request $request)
    {
        $uri = $request->getUri();
        $uri->setPath('/' . $this->version . $uri->getPath());
    }
}