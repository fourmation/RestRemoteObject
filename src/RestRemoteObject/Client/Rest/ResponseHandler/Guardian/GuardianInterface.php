<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Guardian;

use Zend\Http\Response;

interface GuardianInterface
{
    /**
     * @param Response $response
     */
    public function guard(Response $response);
}