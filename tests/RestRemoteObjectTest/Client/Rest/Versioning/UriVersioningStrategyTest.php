<?php

namespace RestRemoteObjectTest\Client\Rest\Versioning;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Versioning\UriVersioningStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class UriVersioningStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $strategy = new UriVersioningStrategy('v3');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Versioning\VersioningStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->version($context);

        $this->assertEquals('http://localhost/v3/resource/1?user=1', $request->getUri()->toString());
    }

    public function testCanVersionApiWithBaseUrl()
    {
        $strategy = new UriVersioningStrategy('v3', 'remote');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Versioning\VersioningStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/remote/api/resource/1?user=1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->version($context);

        $this->assertEquals('http://localhost/remote/v3/api/resource/1?user=1', $request->getUri()->toString());
    }
}
