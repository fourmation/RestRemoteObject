<?php

namespace Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest;

use Zend\Http\Request;

class HttAuthenticationStrategy implements AuthenticationStrategyInterface
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

    public function getUser()
    {
        if (null === $this->user) {
            // exception
        }
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getPassword()
    {
        if (null === $this->password) {
            // exception
        }
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}