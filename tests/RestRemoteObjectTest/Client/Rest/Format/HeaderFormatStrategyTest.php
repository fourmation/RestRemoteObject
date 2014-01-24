<?php

namespace RestRemoteObjectTest\Client\Rest\Format;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class HeaderFormatStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $format = new Format(Format::JSON);
        $strategy = new HeaderFormatStrategy($format);

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Format\FormatStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->format($context);

        $headers = $request->getHeaders()->toString();
        $this->assertEquals('Accept: application/json', trim($headers, "\r\n"));
    }
}
