<?php

namespace RestRemoteObjectTestAsset\Models;

class Location
{
    protected $address;

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        return $this->address = $address;
    }
}