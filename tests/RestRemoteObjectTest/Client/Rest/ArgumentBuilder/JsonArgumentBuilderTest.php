<?php

namespace RestRemoteObjectTest\Client\Rest\ArgumentBuilder;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\ResourceDescriptor;
use RestRemoteObject\Client\Rest\ArgumentBuilder\JsonArgumentBuilder;

use Zend\Http\Client as HttpClient;

use PHPUnit_Framework_TestCase;

class JsonArgumentBuilderTest extends  PHPUnit_Framework_TestCase
{
    public function testCanConvertJsonArguments()
    {
        $context = new Context();
        $descriptor = new ResourceDescriptor(__CLASS__ . '.' . __FUNCTION__, array('foo', 'bar'));
        $context->setResourceDescriptor($descriptor);

        $argBuilder = new JsonArgumentBuilder();
        $output = $argBuilder->build($context);

        $this->assertEquals(array('["foo","bar"]'), $output);

        $client = new HttpClient();
        $client->setParameterPost($output);

        $this->assertEquals($client->getRequest()->getPost()->toArray(), $output);
    }
}
