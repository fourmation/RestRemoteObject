<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

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

        $strategy->version($request);

        $this->assertEquals('http://localhost/v3/resource/1?user=1', $request->getUri()->toString());
    }
}