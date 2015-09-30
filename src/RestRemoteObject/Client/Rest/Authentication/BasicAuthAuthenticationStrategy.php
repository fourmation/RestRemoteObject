<?php

namespace RestRemoteObject\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException;

use RestRemoteObject\Client\Rest\Context;
use Zend\Http\Client;


class BasicAuthAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client, $user = null, $password = null)
    {
        $this->client = $client;
    }

    /**
     * Authenticate the request
     * @param  Context $context
     * @return void
     */
    public function authenticate(Context $context)
    {
        $user = $this->getUser();
        $password = $this->getPassword();

        $this->client->setAuth($user, $password);
    }

    /**
     * Get the username
     * @return string
     * @throws \RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException
     */
    public function getUser()
    {
        if (null === $this->user) {
            throw new MissingAuthenticationParameterException('Username must be defined');
        }

        return $this->user;
    }

    /**
     * Set the username
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the password
     * @return string
     * @throws \RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException
     */
    public function getPassword()
    {
        if (null === $this->password) {
            throw new MissingAuthenticationParameterException('Password must be defined');
        }

        return $this->password;
    }

    /**
     * Set the password
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
