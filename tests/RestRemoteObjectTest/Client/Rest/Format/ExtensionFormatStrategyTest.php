<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Format\ExtensionFormatStrategy;
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\FormatStrategyInterface;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class ExtensionFormatStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanVersionApi()
    {
        $format = new Format(Format::JSON);
        $strategy = new ExtensionFormatStrategy($format);

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Format\FormatStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->format($context);

        $uri = $request->getUri()->toString();
        $this->assertEquals('http://localhost/resource/1.json?user=1', $uri);
    }
}