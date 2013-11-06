<?php

namespace Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest;

use Zend\Http\Request;

class QueryAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * Authenticate the request
     * @param Request $request
     * @return void
     */
    public function authenticate(Request $request)
    {
        $privateKey = $this->getPrivateKey();
        $publicKey = $this->getPublicKey();

        $uri = $request->getUri();
        $query = $uri->getQuery();

        $query .= '&api_key=' . $publicKey;
        $uri->setQuery($query);
    }

    public function getPublicKey()
    {
        if (null === $this->publicKey) {
            // exception
        }
        return $this->publicKey;
    }

    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function getPrivateKey()
    {
        if (null === $this->privateKey) {
            // exception
        }
        return $this->privateKey;
    }

    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }
}