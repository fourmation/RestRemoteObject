<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Client\Rest\MethodDescriptor;
use RestRemoteObjectTestAsset\Models\Location;
use RestRemoteObjectTestAsset\Options\PaginationOptions;

use PHPUnit_Framework_TestCase;

class MethodDescriptorTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetMetaData()
    {
        $location = new Location();
        $location->setId(1);

        $rand = rand(10, 20);
        $pagination = new PaginationOptions(0, $rand);

        $descriptor = new MethodDescriptor('\RestRemoteObjectTestAsset\Services\UserServiceMock.getUsersFromLocation', array($location, $pagination));
        $this->assertEquals('GET', $descriptor->getHttpMethod());
        $this->assertEquals('/users?location=1&offset=0&limit=' . $rand, $descriptor->getApiResource());
        $this->assertEquals('\RestRemoteObjectTestAsset\Models\User', $descriptor->getReturnType());
    }
}