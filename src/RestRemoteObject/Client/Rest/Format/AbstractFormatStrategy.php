<?php

namespace RestRemoteObject\Client\Rest\Format;

abstract class AbstractFormatStrategy implements FormatStrategyInterface
{
    /**
     * @var string $format
     */
    protected $format;

    /**
     * @param Format $format
     */
    public function __construct(Format $format = null)
    {
        if ($format) {
            $this->setFormat($format);
        }
    }

    /**
     * Get format
     *
     * @return Format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set Format
     *
     * @param Format $format
     */
    public function setFormat(Format $format)
    {
        $this->format = $format;
    }
}
