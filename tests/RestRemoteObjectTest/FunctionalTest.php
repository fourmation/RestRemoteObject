<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Versioning\HeaderVersioningStrategy;
use RestRemoteObject\Client\Rest\Authentication\TokenAuthenticationStrategy;

use RestRemoteObjectTestAsset\Models\User;
use RestRemoteObjectTestAsset\Options\PaginationOptions;
use RestRemoteObjectMock\HttpClient;

use PHPUnit_Framework_TestCase;
use ProxyManager\Factory\RemoteObjectFactory;

class FunctionalTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RestClient
     */
    protected $restClient;

    protected $httpClient;

    protected $remote;

    public function setUp()
    {
        $this->restClient = new RestClient('http://my-company.com/rest');
        $this->restClient->setHttpClient($this->httpClient = new HttpClient());

        $factory = new RemoteObjectFactory(
            new RestAdapter($this->restClient)
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

    public function testCanVersionApi()
    {
        $this->restClient->setVersioningStrategy(new HeaderVersioningStrategy('v3', 'json'));
        $this->remote->get(1);

        $lastRequest = $this->httpClient->getLastRawRequest();
        $this->assertEquals("GET http://my-company.com/rest/locations/1 HTTP/1.1\r\nRest-Version: v3+json", trim($lastRequest,  "\r\n"));
    }

    public function testCanAuthenticateRequest()
    {
        $this->restClient->setAuthenticationStrategy(new TokenAuthenticationStrategy('qwerty'));
        $this->remote->get(1);

        $lastRequest = $this->httpClient->getLastRawRequest();
        $this->assertEquals("GET http://my-company.com/rest/locations/1?token=qwerty HTTP/1.1", trim($lastRequest,  "\r\n"));
    }

    public function testCanAddTimestampFeature()
    {
        $this->restClient->addFeature(new RestClient\Feature\Timestamp());
        $this->remote->get(1);

        $lastRequest = $this->httpClient->getLastRawRequest();
        $this->assertEquals("GET http://my-company.com/rest/locations/1?t=" . time() . ' HTTP/1.1', trim($lastRequest,  "\r\n"));
    }
}