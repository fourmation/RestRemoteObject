<?php

namespace RestRemoteObjectTest\Client\Rest\Format;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Format\UriFormatStrategy;
use RestRemoteObject\Client\Rest\Format\Format;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class UriFormatStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCanFormatApi()
    {
        $format = new Format(Format::JSON);
        $strategy = new UriFormatStrategy($format, '/resource');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Format\FormatStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->format($context);

        $uri = $request->getUri()->toString();
        $this->assertEquals('http://localhost/resource/json/1', $uri);
    }

    public function testCanFormatApiAtTheEndOfThePath()
    {
        $format = new Format(Format::JSON);
        $strategy = new UriFormatStrategy($format);

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Format\FormatStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->format($context);

        $uri = $request->getUri()->toString();
        $this->assertEquals('http://localhost/json/resource/1', $uri);
    }
}
