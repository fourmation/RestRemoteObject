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
}
