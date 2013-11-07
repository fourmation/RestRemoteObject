<?php

namespace RestRemoteObjectMock;

use Zend\Server\Client as BaseClient;

class RestClient implements  BaseClient
{
    public $method;

    public function call($method, $params = array())
    {
        $this->method = $method;
    }
}