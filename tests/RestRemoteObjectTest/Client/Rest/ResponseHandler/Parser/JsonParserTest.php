<?php

namespace RestRemoteObjectTest\Client\Rest\ResponseHandler\Parser;

use RestRemoteObject\Client\Rest\Context;
use PHPUnit_Framework_TestCase;
use RestRemoteObject\Client\Rest\ResponseHandler\Parser\JsonParser;

class JsonParserTest extends PHPUnit_Framework_TestCase
{
    public function testCanParseJson()
    {
        $data = array('foo' => 'bar');

        $context = new Context();

        $parser = new JsonParser();
        $new = $parser->parse(json_encode($data), $context);

        $this->assertEquals($data, $new);
    }

    public function testCanParsePartOfJson()
    {
        $data = array('baz' => array('foo' => 'bar'));

        $context = new Context();

        $parser = new JsonParser();
        $parser->setKey('baz');
        $new = $parser->parse(json_encode($data), $context);

        $this->assertEquals($data['baz'], $new);
    }
}
