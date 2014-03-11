<?php

namespace RestRemoteObjectTest\Client\Rest\Authentication\OAuth;

use PHPUnit_Framework_TestCase;

use RestRemoteObject\Client\Rest\Authentication\OAuth\ClientCredentialsGrantAuthenticationStrategy;
use RestRemoteObject\Client\Rest\Context;

class ClientCredentialsGrantAuthenticationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCannotUseRsaSha1WithoutCertificate()
    {
        $auth = new ClientCredentialsGrantAuthenticationStrategy('key', 'secret', OAUTH_SIG_METHOD_RSASHA1);

        $this->setExpectedException('RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException', 'certificate');
        $auth->authenticate(new Context());
    }
}
