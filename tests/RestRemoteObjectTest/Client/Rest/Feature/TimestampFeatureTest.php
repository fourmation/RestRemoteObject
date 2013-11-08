<?php

namespace RestRemoteObjectTest\Client\Rest\Feature;

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

        $strategy->apply($request);

        $this->assertEquals('http://localhost/resource/1?t=' . time(), $request->getUri()->toString());
    }
}