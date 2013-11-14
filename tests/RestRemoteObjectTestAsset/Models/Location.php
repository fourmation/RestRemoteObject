<?php

namespace RestRemoteObjectTestAsset\Models;

use RestRemoteObject\Client\Rest\RestParametersAware;

class Location implements RestParametersAware
{
    protected $id;

    protected $address;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        return $this->address = $address;
    }

    public function getRestParameters()
    {
        return array('location' => $this->getId());
    }
}