<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Parser;

use RestRemoteObject\Client\Rest\Context;

interface ParserInterface
{
    /**
     * Parse response content
     *
     * @param  string  $content
     * @param  Context $context
     * @return array
     */
    public function parse($content, Context $context);
}
