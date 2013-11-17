<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\ResourceDescriptor;

use PHPUnit_Framework_TestCase;

class ContextTest extends PHPUnit_Framework_TestCase
{
    public function testCanConstructContext()
    {
        $resource = new ResourceDescriptor(__CLASS__ . '.' . __FUNCTION__);

        $context = new Context();
        $context->setResourceDescriptor($resource);

        $this->assertEquals($resource, $context->getResourceDescriptor());
    }
}