<?php

namespace RestRemoteObjectTest;

use RestRemoteObject\Client\Rest\Resource\Binder;

use PHPUnit_Framework_TestCase;

class BinderTest extends PHPUnit_Framework_TestCase
{
    public function testCanConstructEmptyBinder()
    {
        $binder = new Binder();

        $this->assertEquals(null, $binder->getParams());
        $this->assertEquals(null, $binder->getObject());
    }

    public function testCanConstructParamsBinder()
    {
        $binder = new Binder(array('foo'));

        $this->assertEquals(array('foo'), $binder->getParams());
        $this->assertEquals(null, $binder->getObject());
    }

    public function testCanConstructObjectBinder()
    {
        $obj = new \stdClass();

        $binder = new Binder($obj);

        $this->assertEquals(null, $binder->getParams());
        $this->assertEquals($obj, $binder->getObject());
    }
}