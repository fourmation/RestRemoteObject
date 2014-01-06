<?php

namespace RestRemoteObject\Client\Rest\Exception;

use Zend\Http\Response;
use Exception;

class RuntimeMethodException extends \RuntimeException implements
    RemoteExceptionInterface
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Response  $response
     * @param string    $message
     * @param int       $code     [optional] The Exception code.
     * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct(Response $response, $message, $code = 0, Exception $previous = null)
    {
        $this->setResponse($response);
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->getResponse()->getStatusCode();
    }
}
