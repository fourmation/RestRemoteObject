<?php

namespace RestRemoteObjectTest\Adapter;

use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObjectMock\RestClient;

use PHPUnit_Framework_TestCase;

class RestTest extends PHPUnit_Framework_TestCase
{
    public function testGetServiceName()
    {
        $client = new RestClient('http://my-company.com/rest');
        $adapter = new RestAdapter($client);
        $adapter->call('MyClass', 'myMethod');

        $this->assertEquals('MyClass.myMethod', $client->method);
    }
}
