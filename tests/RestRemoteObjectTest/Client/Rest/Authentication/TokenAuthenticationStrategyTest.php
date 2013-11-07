<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Authentication\TokenAuthenticationStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class TokenAuthenticationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testAuthenticationWithSimpleResource()
    {
        $strategy = new TokenAuthenticationStrategy();
        $strategy->setToken('12345689');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Authentication\AuthenticationStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1');

        $strategy->authenticate($request);

        $this->assertEquals('http://localhost/resource/1?token=12345689', $request->getUri()->toString());
    }

    public function testAuthenticationWithResourceWithParameters()
    {
        $strategy = new TokenAuthenticationStrategy();
        $strategy->setToken('12345689');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Authentication\AuthenticationStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?foo=bar');

        $strategy->authenticate($request);

        $this->assertEquals('http://localhost/resource/1?foo=bar&token=12345689', $request->getUri()->toString());
    }

    public function testAuthenticationWithoutToken()
    {
        $strategy = new TokenAuthenticationStrategy();

        $request = new Request();
        $request->setUri('http://localhost/resource/1?foo=bar');

        $this->setExpectedException('RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException');
        $strategy->authenticate($request);
    }
}