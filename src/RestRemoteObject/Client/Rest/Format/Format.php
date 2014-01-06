<?php

namespace RestRemoteObject\Client\Rest\Format;

class Format
{
    const JSON = 'json';
    const XML = 'xml';

    /**
     * @var string $format
     */
    protected $format;

    /**
     * @param string $format
     */
    public function __construct($format)
    {
        $this->format = $format;
    }

    /**
     * Format os JSON
     * @return bool
     */
    public function isJson()
    {
        return $this->format == self::JSON;
    }

    /**
     * Format is XML
     * @return bool
     */
    public function isXml()
    {
        return $this->format == self::XML;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->format;
    }
}
