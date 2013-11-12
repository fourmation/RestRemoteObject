<?php

namespace RestRemoteObjectTest;

use RestRemoteObjectTestAsset\Models\User;
use RestRemoteObject\Client\Rest\RestParametersAware;
use PHPUnit_Framework_TestCase;

class RestParametersAwareTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetArgs()
    {
        $user = new User();
        $user->setId(1);

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\RestParametersAware', $user);

        $args = $user->getRestParameters();
        $this->assertTrue(is_array($args));
        $this->assertTrue(isset($args['user']));
    }
}