<?php

namespace RestRemoteObject\Client\Rest\Authentication\OAuth;

use OAuth;

use RestRemoteObject\Client\Rest\Authentication\AuthenticationStrategyInterface;
use RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException;
use RestRemoteObject\Client\Rest\Context;

class ClientCredentialsGrantAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var OAuth
     */
    protected $oauth;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $rsaCertificate;

    /**
     * @param $key
     * @param $secret
     * @param  null|void         $signature
     * @param  null|void         $authType
     * @throws \RuntimeException
     */
    public function __construct($key, $secret, $signature = null, $authType = null)
    {
        if (!extension_loaded('oauth')) {
            throw new \RuntimeException('OAuth extension must be loaded');
        }

        $this->key = $key;
        $this->secret = $secret;
        $this->signature = $signature;

        if (null === $signature) {
            $this->oauth = new OAuth($key, $secret);
        } else {
            $this->oauth = new OAuth($key, $secret, $signature);
        }
        if (null !== $authType) {
            $this->oauth->setAuthType($authType);
        }
    }

    /**
     * Authenticate the request
     * @param  Context $context
     * @return void
     */
    public function authenticate(Context $context)
    {
        $this->oauth->setToken($this->key, $this->secret);

        if (OAUTH_SIG_METHOD_RSASHA1 === $this->signature) {
            $certificate = $this->getRsaCertificate();
            $this->oauth->setRSACertificate($certificate);
        }
    }

    /**
     * @return OAuth
     */
    public function getOAuth()
    {
        return $this->oauth;
    }

    /**
     * @param string $rsaCertificate
     */
    public function setRsaCertificate($rsaCertificate)
    {
        $this->rsaCertificate = $rsaCertificate;
    }

    /**
     * @return string
     * @throws \RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException
     */
    public function getRsaCertificate()
    {
        if (OAUTH_SIG_METHOD_RSASHA1 === $this->signature && null === $this->rsaCertificate) {
            throw new MissingAuthenticationParameterException('RSA certificate must be defined');
        }

        return $this->rsaCertificate;
    }
}
