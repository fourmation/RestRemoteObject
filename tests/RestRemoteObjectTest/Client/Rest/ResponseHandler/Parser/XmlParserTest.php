<?php

namespace RestRemoteObjectTest\Client\Rest\ResponseHandler\Parser;

use RestRemoteObject\Client\Rest\Context;
use PHPUnit_Framework_TestCase;
use RestRemoteObject\Client\Rest\ResponseHandler\Parser\XmlParser;

class XmlParserTest extends PHPUnit_Framework_TestCase
{
    public function testCanParseXMl()
    {
        $data = <<<'XML'
<?xml version='1.0'?>
    <document>
        <foo>bar</foo>
    </document>
XML;

        $context = new Context();

        $parser = new XmlParser();
        $new = $parser->parse($data, $context);

        $this->assertEquals(array('foo' => 'bar'), $new);
    }

    public function testCanParsePartOfJson()
    {
        $data = <<<'XML'
<?xml version='1.0'?>
    <document>
        <baz>
            <foo>bar</foo>
        </baz>
    </document>
XML;

        $context = new Context();

        $parser = new XmlParser();
        $parser->setKey('baz');
        $new = $parser->parse($data, $context);

        $this->assertEquals(array('foo' => 'bar'), $new);
    }
}
