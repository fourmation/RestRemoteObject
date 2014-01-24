<?php

namespace RestRemoteObjectTest\Client\Rest\Resource;

use RestRemoteObject\Client\Rest\Resource\Binder;
use RestRemoteObject\Client\Rest\Resource\Descriptor;
use RestRemoteObjectTestAsset\Models\Location;
use RestRemoteObjectTestAsset\Options\PaginationOptions;

use PHPUnit_Framework_TestCase;

class DescriptorTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetMetaData()
    {
        $descriptor = new Descriptor('\RestRemoteObjectTestAsset\Services\UserServiceMock.getUsersFromLocation');

        $this->assertEquals('GET', $descriptor->getHttpMethod());
        $this->assertEquals('/users?location=%location&offset=%offset&limit=%limit', $descriptor->getApiResource());
        $this->assertEquals('RestRemoteObjectTestAsset\Models\User', $descriptor->getReturnType());
    }

    public function testCanBindDescriptor()
    {
        $location = new Location();
        $location->setId(1);

        $rand = rand(10, 20);
        $pagination = new PaginationOptions(0, $rand);

        $binder = new Binder(array($location, $pagination));
        $descriptor = new Descriptor('\RestRemoteObjectTestAsset\Services\UserServiceMock.getUsersFromLocation');
        $descriptor->bind($binder);

        $this->assertEquals('GET', $descriptor->getHttpMethod());
        $this->assertEquals('/users?location=1&offset=0&limit=' . $rand, $descriptor->getApiResource());
        $this->assertEquals('RestRemoteObjectTestAsset\Models\User', $descriptor->getReturnType());
        $this->assertTrue($descriptor->isReturnAsArray());
    }
}
