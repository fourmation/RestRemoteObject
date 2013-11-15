<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Parser;

interface ParserInterface
{
    /**
     * Parse response content
     *
     * @param $content
     * @return array
     */
    public function parse($content);
}