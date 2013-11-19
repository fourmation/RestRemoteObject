<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Versioning\HeaderVersioningStrategy;
use RestRemoteObject\Client\Rest\Authentication\TokenAuthenticationStrategy;
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;
use RestRemoteObject\Client\Rest\ResponseHandler\Builder\GhostObjectBuilder;
use RestRemoteObject\Client\Rest\Exception\RuntimeMethodException;

use RestRemoteObjectTestAsset\Builder\UserBuilder;
use RestRemoteObjectTestAsset\Models\Location;
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
        $this->restClient->setFormatStrategy(new HeaderFormatStrategy(new Format(Format::JSON)));
        $this->restClient->setHttpClient($this->httpClient = new HttpClient());

        $factory = new RemoteObjectFactory(
            new RestAdapter($this->restClient)
        );
        $this->remote = $factory->createProxy('RestRemoteObjectTestAsset\Services\UserServiceInterface');
    }

    public function testCanProxyService()
    {
        $this->assertInstanceOf('RestRemoteObjectTestAsset\Services\UserServiceInterface', $this->remote);
    }

    public function testCanMakeAGETRequest()
    {
        $user = $this->remote->get(1);
        $this->assertInstanceOf('RestRemoteObjectTestAsset\Models\User', $user);
        $this->assertEquals('Vincent', $user->getName());
    }

    public function testCanMakeAPOSTRequest()
    {
        $user = $this->remote->create(array('name' => 'Dave'));
        $this->assertInstanceOf('RestRemoteObjectTestAsset\Models\User', $user);
        $this->assertEquals('Dave', $user->getName());
    }

    public function testCanMakeAPaginatedRequest()
    {
        $location = new Location();
        $location->setId(1);

        $pagination = new PaginationOptions(0, 20);

        $users = $this->remote->getUsersFromLocation($location, $pagination);
        $this->assertEquals(2, count($users));
    }

    public function testCanVersionApi()
    {
        $this->restClient->setVersioningStrategy(new HeaderVersioningStrategy('v3'));
        $this->remote->get(1);

        $lastRequest = $this->httpClient->getLastRawRequest();
        $this->assertEquals("GET http://my-company.com/rest/users/1 HTTP/1.1\r\nRest-Version: v3\r\nAccept: application/json", trim($lastRequest,  "\r\n"));
    }

    public function testCanAuthenticateRequest()
    {
        $this->restClient->setAuthenticationStrategy(new TokenAuthenticationStrategy('qwerty'));
        $this->remote->get(1);

        $lastRequest = $this->httpClient->getLastRawRequest();
        $this->assertEquals("GET http://my-company.com/rest/users/1?token=qwerty HTTP/1.1\r\nAccept: application/json", trim($lastRequest,  "\r\n"));
    }

    public function testCanAddBuilder()
    {
        $this->restClient->addBuilder(new UserBuilder());
        $this->remote->get(1);

        $lastRequest = $this->httpClient->getLastRawRequest();
        $this->assertEquals("GET http://my-company.com/rest/users/2" . " HTTP/1.1\r\nAccept: application/json", trim($lastRequest,  "\r\n"));
    }

    public function testCanAddTimestampFeature()
    {
        $this->restClient->addFeature(new RestClient\Feature\TimestampFeature());
        $this->remote->get(1);

        $lastRequest = $this->httpClient->getLastRawRequest();
        $this->assertEquals("GET http://my-company.com/rest/users/1?t=" . time() . " HTTP/1.1\r\nAccept: application/json", trim($lastRequest,  "\r\n"));
    }

    public function testCanPilotResultObject()
    {
        $responseHandler = $this->restClient->getResponseHandler();
        $responseHandler->setResponseBuilder(new GhostObjectBuilder($this->restClient));
        $user = $this->remote->get(1);
        $locations = $user->getLocations();

        $this->assertEquals(1, count($locations));
    }

    public function testCanCallBadResource()
    {
        try {
            $this->remote->badResource();
        } catch (RuntimeMethodException $e) {
            $this->assertInstanceOf('RestRemoteObject\Client\Rest\Exception\RemoteExceptionInterface', $e);
            $this->assertInstanceOf('Zend\Http\Response', $e->getResponse());
        }

    }
}