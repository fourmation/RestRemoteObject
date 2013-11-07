<?php

namespace RestRemoteObject\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException;

use Zend\Crypt\Hmac;
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
        $query = $uri->getQueryAsArray();

        $signature = Hmac::compute($privateKey, 'sha1', $uri->getPath() . '?' . $uri->getQuery());
        $query['public_key'] = $publicKey;
        $query['signature'] = $signature;

        $uri->setQuery($query);
    }

    /**
     * Get the public key
     * @return string
     * @throws MissingAuthenticationParameterException
     */
    public function getPublicKey()
    {
        if (null === $this->publicKey) {
            throw new MissingAuthenticationParameterException('Public key must be defined');
        }
        return $this->publicKey;
    }

    /**
     * Set the public key
     * @param $publicKey
     * @return $this
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get the private key
     * @return string
     * @throws MissingAuthenticationParameterException
     */
    public function getPrivateKey()
    {
        if (null === $this->privateKey) {
            throw new MissingAuthenticationParameterException('Private key must be defined');
        }
        return $this->privateKey;
    }

    /**
     * Set the private key
     * @param $privateKey
     * @return $this
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }
}