<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Format\ParameterFormatStrategy;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class ParameterFormatStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $strategy = new ParameterFormatStrategy(FormatStrategyInterface::JSON);

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Format\FormatStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $strategy->format($request);

        $this->assertEquals('http://localhost/resource/1?user=1&f=json', $request->getUri()->toString());
    }
}