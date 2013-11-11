<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class HeaderFormatStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $strategy = new HeaderFormatStrategy(FormatStrategyInterface::JSON);

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Format\FormatStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $strategy->format($request);

        $headers = $request->getHeaders()->toString();
        $this->assertEquals('Content-type: application/json', trim($headers, "\r\n"));
    }
}