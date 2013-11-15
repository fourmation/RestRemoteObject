<?php

namespace RestRemoteObjectTest\Client\Rest\ResponseHandler\Parser;

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


        $parser = new XmlParser();
        $new = $parser->parse($data);

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

        $parser = new XmlParser();
        $parser->setKey('baz');
        $new = $parser->parse($data);

        $this->assertEquals(array('foo' => 'bar'), $new);
    }
}
