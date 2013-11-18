<?php

namespace RestRemoteObject\Client\Rest\Format;

use RestRemoteObject\Client\Rest\Context;

interface FormatStrategyInterface
{
    /**
     * Format apply
     * @param Context $context
     */
    public function format(Context $context);

    /**
     * Set Format
     *
     * @param Format $format
     */
    public function setFormat(Format $format);

    /**
     * Get format
     *
     * @return Format
     */
    public function getFormat();
}
