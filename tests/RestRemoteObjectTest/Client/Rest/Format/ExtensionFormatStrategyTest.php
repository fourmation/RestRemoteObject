<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Format\ExtensionFormatStrategy;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class ExtensionFormatStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $strategy = new ExtensionFormatStrategy(FormatStrategyInterface::JSON);

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Format\FormatStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $strategy->format($request);

        $uri = $request->getUri()->toString();
        $this->assertEquals('http://localhost/resource/1.json?user=1', $uri);
    }
}