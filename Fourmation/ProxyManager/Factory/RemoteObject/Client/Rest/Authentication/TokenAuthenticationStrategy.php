<?php

namespace Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest;

use Zend\Http\Request;

class TokenAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * Authenticate the request
     * @param Request $request
     * @return void
     */
    public function authenticate(Request $request)
    {
        $token = $this->getToken();

        $uri = $request->getUri();
        $query = $uri->getQuery();

        $query .= '&token=' . $token;
        $uri->setQuery($query);
    }

    public function getToken()
    {
        if (null === $this->token) {
            // exception
        }
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
}