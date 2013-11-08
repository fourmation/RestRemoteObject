<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\MethodDescriptor;

use Zend\Http\Response;

interface ResponseHandlerInterface
{
    CONST JSON_RESPONSE = 'json_response';
    CONST XML_RESPONSE = 'xml_response';

        /**
     * @param $format
     * @param MethodDescriptor $descriptor
     * @param Response $response
     * @return array
     */
    public function buildResponse($format, MethodDescriptor $descriptor, Response $response);
}
