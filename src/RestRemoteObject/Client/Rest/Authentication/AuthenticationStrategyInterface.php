<?php

namespace RestRemoteObject\Client\Rest\Authentication;

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