<?php

namespace RestRemoteObjectTest\Client\Rest\Feature;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Feature\TimestampFeature;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class TimestampFeatureTest extends PHPUnit_Framework_TestCase
{
    public function testCanAddTimestamp()
    {
        $strategy = new TimestampFeature();
        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Feature\FeatureInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->apply($context);

        $this->assertEquals('http://localhost/resource/1?t=' . time(), $request->getUri()->toString());
    }
}
