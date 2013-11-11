<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Client\Rest\MethodDescriptor;
use RestRemoteObjectTestAsset\Models\User;
use RestRemoteObjectTestAsset\Options\PaginationOptions;

use PHPUnit_Framework_TestCase;

class MethodDescriptorTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetMetaData()
    {
        $user = new User();
        $user->setId(1);

        $rand = rand(10, 20);
        $pagination = new PaginationOptions(0, $rand);

        $descriptor = new MethodDescriptor('\RestRemoteObjectTestAsset\Services\LocationServiceMock.getAllFromUser', array($user, $pagination));
        $this->assertEquals('GET', $descriptor->getHttpMethod());
        $this->assertEquals('/locations/?user=1&offset=0&limit=' . $rand, $descriptor->getApiResource());
        $this->assertEquals('\RestRemoteObjectTestAsset\Models\Location', $descriptor->getReturnType());
    }
}