<?php

namespace RestRemoteObjectTest;

use RestRemoteObjectTestAsset\Models\Location;
use PHPUnit_Framework_TestCase;

class RestParametersAwareTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetArgs()
    {
        $location = new Location();
        $location->setId(1);

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\RestParametersAware', $location);

        $args = $location->getRestParameters();
        $this->assertTrue(is_array($args));
        $this->assertTrue(isset($args['location']));
    }
}