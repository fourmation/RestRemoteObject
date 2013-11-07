<?php

namespace RestRemoteObject\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException;

use Zend\Http\Request;

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
     * @param Request $request
     * @return void
     */
    public function authenticate(Request $request)
    {
        $token = $this->getToken();

        $uri = $request->getUri();
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