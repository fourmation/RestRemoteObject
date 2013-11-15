<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\ResourceDescriptor;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use Zend\Http\Response;

interface ResponseHandlerInterface
{
    /**
     * @param FormatStrategyInterface $format
     * @param ResourceDescriptor $descriptor
     * @param Response $response
     * @return array
     */
    public function buildResponse(FormatStrategyInterface $format, ResourceDescriptor $descriptor, Response $response);
}
