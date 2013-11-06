<?php

namespace Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest;

use Zend\Http\Request;

interface AuthenticationStrategyInterface
{
    /**
     * Authenticate the request
     * @param Request $request
     * @return void
     */
    public function authenticate(Request $request);
}