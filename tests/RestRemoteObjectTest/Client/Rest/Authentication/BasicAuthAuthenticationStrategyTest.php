<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Context;
use Zend\Http\Client;
use RestRemoteObject\Client\Rest\Authentication\BasicAuthAuthenticationStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Http\Request;

class BasicAuthenticationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testAuthentication()
    {
        $client = new Client();
        $strategy = new BasicAuthAuthenticationStrategy($client);
        $strategy->setUser('foo');
        $strategy->setPassword('123456');

        $this->assertInstanceOf(
                'RestRemoteObject\Client\Rest\Authentication\BasicAuthAuthenticationStrategy',
                $strategy
                );

        $request = new Request();

        $context = new Context();
        $context->setRequest($request);
        $strategy->authenticate($context);

        $password = $request->getUri()->getPassword();
        $user = $request->getUri()->getUser();

        $this->assertNull($password);
        $this->assertNull($user);
    }

    public function testAuthenticationWithoutValue()
    {
        $client = new Client();
        $strategy = new BasicAuthAuthenticationStrategy($client);

        $request = new Request();

        $context = new Context();
        $context->setRequest($request);

        $this->setExpectedException('RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException');
        $strategy->authenticate($context);
    }
}
