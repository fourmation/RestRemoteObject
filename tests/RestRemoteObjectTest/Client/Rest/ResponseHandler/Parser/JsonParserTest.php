<?php

namespace RestRemoteObjectTest\Client\Rest\ResponseHandler\Parser;

use PHPUnit_Framework_TestCase;
use RestRemoteObject\Client\Rest\ResponseHandler\Parser\JsonParser;

class JsonParserTest extends PHPUnit_Framework_TestCase
{
    public function testCanParseJson()
    {
        $data = array('foo' => 'bar');

        $parser = new JsonParser();
        $new = $parser->parse(json_encode($data));

        $this->assertEquals($data, $new);
    }

    public function testCanParsePartOfJson()
    {
        $data = array('baz' => array('foo' => 'bar'));

        $parser = new JsonParser();
        $parser->setKey('baz');
        $new = $parser->parse(json_encode($data));

        $this->assertEquals($data['baz'], $new);
    }
}
