<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;

use RestRemoteObjectTestAsset\Models\User;
use RestRemoteObjectTestAsset\Options\PaginationOptions;
use RestRemoteObjectMock\HttpClient;

use PHPUnit_Framework_TestCase;
use ProxyManager\Factory\RemoteObjectFactory;

class FunctionalTest extends PHPUnit_Framework_TestCase
{
    protected $remote;

    public function setUp()
    {
        $client = new RestClient('http://my-company.com/rest');
        $client->setHttpClient(new HttpClient());

        $factory = new RemoteObjectFactory(
            new RestAdapter($client)
        );
        $this->remote = $factory->createProxy('RestRemoteObjectTestAsset\Services\LocationServiceInterface');
    }

    public function testCanProxyService()
    {
        $this->assertInstanceOf('RestRemoteObjectTestAsset\Services\LocationServiceInterface', $this->remote);
    }

    public function testCanMakeAGETRequest()
    {
        $location = $this->remote->get(1);
        $this->assertInstanceOf('RestRemoteObjectTestAsset\Models\Location', $location);
        $this->assertEquals('Pitt Street', $location->getAddress());
    }

    public function testCanMakeAPOSTRequest()
    {
        $location = $this->remote->create(array('address' => 'Elizabeth Street', 'city' => 'Sydney'));
        $this->assertInstanceOf('RestRemoteObjectTestAsset\Models\Location', $location);
        $this->assertEquals('Elizabeth Street', $location->getAddress());
    }

    public function testCanMakeAPaginatedRequest()
    {
        $user = new User();
        $user->setId(1);

        $pagination = new PaginationOptions(0, 20);

        $locations = $this->remote->getAllFromUser($user, $pagination);
        $this->assertEquals(2, count($locations));
    }
}