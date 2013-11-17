<?php

namespace RestRemoteObjectTest\Client\Rest\ResponseHandler;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\ResourceDescriptor;
use RestRemoteObject\Client\Rest\Format\Format;

use RestRemoteObject\Client\Rest\ResponseHandler\DefaultResponseHandler;
use PHPUnit_Framework_TestCase;
use Zend\Http\Response;

class DefaultResponseHandlerTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetJsonResponse()
    {
        $format = new Format(Format::JSON);
        $descriptor = new ResourceDescriptor('\RestRemoteObjectTestAsset\Services\UserServiceMock.get', array(1));
        $response = new Response();
        $response->setContent(json_encode(array(array('address' => 'Pitt Street'))));

        $context = new Context();
        $context->setResourceDescriptor($descriptor);
        $context->setFormat($format);

        $handler = new DefaultResponseHandler();
        $object = $handler->buildResponse($context, $response);

        $this->assertInstanceOf('RestRemoteObjectTestAsset\Models\User', $object);
    }
}
