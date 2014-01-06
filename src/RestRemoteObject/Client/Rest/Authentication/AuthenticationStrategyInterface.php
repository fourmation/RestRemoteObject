<?php

namespace RestRemoteObject\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Context;

interface AuthenticationStrategyInterface
{
    /**
     * Authenticate the request
     * @param  Context $context
     * @return void
     */
    public function authenticate(Context $context);
}
