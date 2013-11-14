<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\MethodDescriptor;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use RestRemoteObject\Client\Rest\ResponseHandler\DefaultResponseHandler;
use PHPUnit_Framework_TestCase;
use Zend\Http\Response;

class DefaultResponseHandlerTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetJsonResponse()
    {
        $format = new HeaderFormatStrategy(FormatStrategyInterface::JSON);
        $descriptor = new MethodDescriptor('\RestRemoteObjectTestAsset\Services\UserServiceMock.get', array(1));
        $response = new Response();
        $response->setContent(json_encode(array(array('address' => 'Pitt Street'))));

        $handler = new DefaultResponseHandler();
        $object = $handler->buildResponse($format, $descriptor, $response);

        $this->assertInstanceOf('RestRemoteObjectTestAsset\Models\User', $object);
    }
}
