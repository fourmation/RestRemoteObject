<?php

namespace RestRemoteObjectTest\Client\Rest\Debug\Verbosity;

use RestRemoteObject\Client\Rest\Debug\Verbosity\Verbosity;

use PHPUnit_Framework_TestCase;

class VerbosityTest extends PHPUnit_Framework_TestCase
{
    public function testCanHaveNotVerbosityLevel()
    {
        $verbosity = new Verbosity(Verbosity::TRACE_DISABLED);
        $this->assertFalse($verbosity->hasUriTrace());
        $this->assertFalse($verbosity->hasHttpMethodTrace());
        $this->assertFalse($verbosity->hasHttpParamsTrace());
        $this->assertFalse($verbosity->hasHttpResponseTrace());
        $this->assertFalse($verbosity->hasHttpStatusCodeTrace());
    }

    public function testCanHaveOneVerbosityLevel()
    {
        $verbosity = new Verbosity(Verbosity::TRACE_REQUEST_URI);
        $this->assertTrue($verbosity->hasUriTrace());
        $this->assertFalse($verbosity->hasHttpMethodTrace());
        $this->assertFalse($verbosity->hasHttpParamsTrace());
        $this->assertFalse($verbosity->hasHttpResponseTrace());
        $this->assertFalse($verbosity->hasHttpStatusCodeTrace());
    }

    public function testCanHaveSeveralVerbosityLevel()
    {
        $verbosity = new Verbosity(Verbosity::TRACE_REQUEST_URI | Verbosity::TRACE_REQUEST_HTTP_PARAMS);
        $this->assertTrue($verbosity->hasUriTrace());
        $this->assertFalse($verbosity->hasHttpMethodTrace());
        $this->assertTrue($verbosity->hasHttpParamsTrace());
        $this->assertFalse($verbosity->hasHttpResponseTrace());
        $this->assertFalse($verbosity->hasHttpStatusCodeTrace());
    }
}
