<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Versioning\HeaderVersioningStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class HeaderVersioningStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $strategy = new HeaderVersioningStrategy('v3', 'json');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Versioning\VersioningStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $strategy->version($request);

        $headers = $request->getHeaders()->toString();
        $this->assertEquals('Rest-Version: v3+json', trim($headers, "\r\n"));
    }
}