<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Client\Rest\ResourceDescriptor;
use RestRemoteObjectTestAsset\Models\Location;
use RestRemoteObjectTestAsset\Options\PaginationOptions;

use PHPUnit_Framework_TestCase;

class ResourceDescriptorTest extends PHPUnit_Framework_TestCase
{
    public function testCanGetMetaData()
    {
        $location = new Location();
        $location->setId(1);

        $rand = rand(10, 20);
        $pagination = new PaginationOptions(0, $rand);

        $descriptor = new ResourceDescriptor('\RestRemoteObjectTestAsset\Services\UserServiceMock.getUsersFromLocation', array($location, $pagination));
        $this->assertEquals('GET', $descriptor->getHttpMethod());
        $this->assertEquals('/users?location=1&offset=0&limit=' . $rand, $descriptor->getApiResource());
        $this->assertEquals('\RestRemoteObjectTestAsset\Models\User', $descriptor->getReturnType());
    }
}