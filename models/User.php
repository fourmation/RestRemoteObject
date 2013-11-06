<?php

use Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest\RestParametersAware;

class User implements RestParametersAware
{
    protected $id;

    public function getId() { return $this->id; }
    public function setId($id) { return $this->id = $id; }

    public function getRestParameters()
    {
        return array('user' => $this->getId());
    }
}