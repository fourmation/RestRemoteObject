<?php

namespace RestRemoteObject\Client\Rest\Versioning;

use Zend\Http\Request;

class HeaderVersioningStrategy implements VersioningStrategyInterface
{
    /**
     * @var string $version
     */
    protected $version;

    /**
     * @var string $format
     */
    protected $format;

    /**
     * @var string $header
     */
    protected $header;

    /**
     * @param string $version
     * @param string $format
     * @param string $header
     */
    public function __construct($version, $format, $header = 'Rest-Version')
    {
        $this->version = $version;
        $this->format = $format;
        $this->header = $header;
    }

    /**
     * Version the request
     * @param Request $request
     * @return void
     */
    public function version(Request $request)
    {
        $request->getHeaders()->addHeaderLine($this->header . ': ' . $this->version . '+' . $this->format);

    }
}