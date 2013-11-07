<?php

namespace RestRemoteObject\Client\Rest\Versioning;

use Zend\Http\Request;

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
     * @param Request $request
     * @return void
     */
    public function version(Request $request)
    {
        $uri = $request->getUri();
        $query = $uri->getQueryAsArray();

        $query['v'] = $this->version;
        $uri->setQuery($query);
    }
}