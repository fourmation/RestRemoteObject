<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\MethodDescriptor;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use Zend\Http\Response;

interface ResponseHandlerInterface
{
    /**
     * @param FormatStrategyInterface $format
     * @param MethodDescriptor $descriptor
     * @param Response $response
     * @return array
     */
    public function buildResponse(FormatStrategyInterface $format, MethodDescriptor $descriptor, Response $response);
}
