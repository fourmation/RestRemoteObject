<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Authentication\QueryAuthenticationStrategy;

use PHPUnit_Framework_TestCase;
use Zend\Crypt\Hmac;
use Zend\Http\Request;

class QueryAuthenticationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testAuthenticationWithSimpleResource()
    {
        $strategy = new QueryAuthenticationStrategy();
        $strategy->setPublicKey('12345689');
        $strategy->setPrivateKey('qwerty');

        $this->assertInstanceOf('RestRemoteObject\Client\Rest\Authentication\AuthenticationStrategyInterface', $strategy);

        $request = new Request();
        $request->setUri('http://localhost/resource/1?user=1');

        $context = new Context();
        $context->setRequest($request);

        $strategy->authenticate($context);

        $query = $request->getUri()->getQueryAsArray();
        $signature = $query['signature'];
        $compute = Hmac::compute('qwerty', 'sha1', '/resource/1?user=1');
        $this->assertEquals($compute, $signature);
        $this->assertEquals('http://localhost/resource/1?user=1&public_key=12345689&signature=' . $compute, $request->getUri()->toString());
    }

    public function testAuthenticationWithoutToken()
    {
        $strategy = new QueryAuthenticationStrategy();

        $request = new Request();
        $request->setUri('http://localhost/resource/1?foo=bar');

        $this->setExpectedException('RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException');

        $context = new Context();
        $context->setRequest($request);

        $strategy->authenticate($context);
    }
}