<?php

namespace RestRemoteObjectTest\Client\Rest\Versioning;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Versioning\ParameterVersioningStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class ParameterVersioningStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $strategy = new ParameterVersioningStrategy('v3');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Versioning\VersioningStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->version($context);

        $this->assertEquals('http://localhost/resource/1?user=1&v=v3', $request->getUri()->toString());
    }
}
