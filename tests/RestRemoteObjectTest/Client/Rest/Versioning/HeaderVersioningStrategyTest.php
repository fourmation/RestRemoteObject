<?php

namespace RestRemoteObjectTest\Client\Rest\Versioning;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Versioning\HeaderVersioningStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class HeaderVersioningStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $strategy = new HeaderVersioningStrategy('v3');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Versioning\VersioningStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->version($context);

        $headers = $request->getHeaders()->toString();
        $this->assertEquals('Rest-Version: v3', trim($headers, "\r\n"));
    }
}
