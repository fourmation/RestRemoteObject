<?php

namespace RestRemoteObject\Client\Rest\Exception;

use Zend\Http\Response;

interface RemoteExceptionInterface
{
    /**
     * @return int
     */
    public function getHttpStatus();

    /**
     * @return Response
     */
    public function getResponse();
}