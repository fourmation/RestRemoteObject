<?php

namespace RestRemoteObject\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Context;
use RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException;

class TokenAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @param string $token
     */
    public function __construct($token = null)
    {
        if ($token) {
            $this->setToken($token);
        }
    }

    /**
     * Authenticate the request
     * @param Context $context
     * @return void
     */
    public function authenticate(Context $context)
    {
        $token = $this->getToken();

        $uri = $context->getRequest()->getUri();
        $query = $uri->getQueryAsArray();

        $query['token'] = $token;
        $uri->setQuery($query);
    }

    /**
     * Get the token
     * @return string
     * @throws \RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException
     */
    public function getToken()
    {
        if (null === $this->token) {
            throw new MissingAuthenticationParameterException('Token must be defined');
        }
        return $this->token;
    }

    /**
     * Set the token
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}