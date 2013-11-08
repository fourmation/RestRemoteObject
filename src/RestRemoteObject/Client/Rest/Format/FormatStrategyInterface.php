<?php

namespace RestRemoteObject\Client\Rest\Format;

use Zend\Http\Request;

interface FormatStrategyInterface
{
    CONST JSON = 'json';
    CONST XML  = 'xml';

    /**
     * Format apply
     * @param Request $request
     */
    public function format(Request $request);
}
