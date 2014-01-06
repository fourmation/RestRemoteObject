<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Resource\Descriptor;

use PHPUnit_Framework_TestCase;

class ContextTest extends PHPUnit_Framework_TestCase
{
    public function testCanConstructContext()
    {
        $resource = new Descriptor(__CLASS__ . '.' . __FUNCTION__);

        $context = new Context();
        $context->setResourceDescriptor($resource);

        $this->assertEquals($resource, $context->getResourceDescriptor());
    }
}
