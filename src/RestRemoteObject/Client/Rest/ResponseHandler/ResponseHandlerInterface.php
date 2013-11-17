<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\Context;

use Zend\Http\Response;

interface ResponseHandlerInterface
{
    /**
     * @param Context $context
     * @param Response $response
     * @return array
     */
    public function buildResponse(Context $context, Response $response);
}
