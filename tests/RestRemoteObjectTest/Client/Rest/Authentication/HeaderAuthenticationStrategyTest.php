<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Authentication\HeaderAuthenticationStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class HeaderAuthenticationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testAuthentication()
    {
        $strategy = new HeaderAuthenticationStrategy();
        $strategy->setHeader('X-ApiKey');
        $strategy->setValue('123456');

        $this->assertInstanceOf(
            'RestRemoteObject\Client\Rest\Authentication\HeaderAuthenticationStrategy',
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

        $header = $request->getHeaders()->toString();

        $this->assertEquals('X-ApiKey: 123456', trim($header));
    }

    public function testAuthenticationWithoutValue()
    {
        $strategy = new HeaderAuthenticationStrategy();

        $request = new Request();

        $context = new Context();
        $context->setRequest($request);

        $this->setExpectedException('RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException');
        $strategy->authenticate($context);
    }
}
