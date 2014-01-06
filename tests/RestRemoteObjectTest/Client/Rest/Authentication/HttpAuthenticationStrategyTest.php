<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Authentication\HttpAuthenticationStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class HttpAuthenticationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testAuthentication()
    {
        $strategy = new HttpAuthenticationStrategy();
        $strategy->setUser('foo');
        $strategy->setPassword('123456');

        $this->assertInstanceOf(
            'RestRemoteObject\Client\Rest\Authentication\AuthenticationStrategyInterface',
            $strategy
        );

        $request = new Request();
        $password = $request->getUri()->getPassword();
        $user = $request->getUri()->getUser();

        $this->assertEquals(null, $password);
        $this->assertEquals(null, $user);

        $context = new Context();
        $context->setRequest($request);
        $strategy->authenticate($context);

        $password = $request->getUri()->getPassword();
        $user = $request->getUri()->getUser();

        $this->assertEquals('123456', $password);
        $this->assertEquals('foo', $user);
    }

    public function testAuthenticationWithoutLogin()
    {
        $strategy = new HttpAuthenticationStrategy();

        $request = new Request();

        $context = new Context();
        $context->setRequest($request);

        $this->setExpectedException('RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException');
        $strategy->authenticate($context);
    }
}
