<?php

namespace RestRemoteObject\Client\Rest\ResponseHandler\Guardian;

use Zend\Http\Response;
use RestRemoteObject\Client\Rest\Exception\ResponseErrorException;

class HeadersGuardian implements GuardianInterface
{
    /**
     * @var array
     */
    protected $errorsHeaders = array();

    /**
     * @var array
     */
    protected $warningHeaders = array();

    /**
     * @param array $errorsHeaders
     * @param array $warningHeaders
     */
    public function __construct(array $errorsHeaders, array $warningHeaders = array())
    {
        $this->errorsHeaders = $errorsHeaders;
        $this->warningHeaders = $warningHeaders;
    }

    /**
     * @param Response $response
     * @throws \RestRemoteObject\Client\Rest\Exception\ResponseErrorException
     */
    public function guard(Response $response)
    {
        $headers = $response->getHeaders();
        foreach($this->errorsHeaders as $errorsHeader) {
            $header = $headers->get($errorsHeader);
            if ($header) {
                throw new ResponseErrorException(sprintf('Error header found "%s"', $header->getFieldValue()));
            }
        }
        foreach($this->warningHeaders as $errorsHeader) {
            $header = $headers->get($errorsHeader);
            if ($header) {
                trigger_error(sprintf('Warning header found "%s"', $header->getFieldValue()), E_USER_WARNING);
            }
        }
    }
}