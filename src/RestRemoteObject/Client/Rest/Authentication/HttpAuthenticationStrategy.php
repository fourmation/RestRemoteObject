<?php

namespace RestRemoteObject\Client\Rest\Authentication;

use RestRemoteObject\Client\Rest\Exception\MissingAuthenticationParameterException;

use Zend\Http\Request;

class HttpAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    public function __construct($user = null, $password = null)
    {
        if ($user) {
            $this->setUser($user);
        }
        if ($password) {
            $this->setPassword($password);
        }
    }

    /**
     * Authenticate the request
     * @param Request $request
     * @return void
     */
    public function authenticate(Request $request)
    {
        $user = $this->getUser();
        $password = $this->getPassword();

        $uri = $request->getUri();
        $uri->setUser($user);
        $uri->setPassword($password);
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