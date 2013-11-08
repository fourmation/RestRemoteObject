<?php

namespace RestRemoteObject\Client\Rest\Format;

abstract class AbstractFormatStrategy implements FormatStrategyInterface
{
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
        return $this->format == FormatStrategyInterface::JSON;
    }

    /**
     * Format is XML
     * @return bool
     */
    public function isXml()
    {
        return $this->format == FormatStrategyInterface::XML;
    }
}
