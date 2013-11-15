<?php

namespace RestRemoteObjectTestAsset\Models;

class User
{
    protected $id;

    protected $name;

    protected $locations;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        return $this->name = $name;
    }

    /**
     * @rest\http GET
     * @rest\uri /locations?user=:getId
     * @return \RestRemoteObjectTestAsset\Models\Location[]
     */
    public function getLocations()
    {
        return $this->locations;
    }

    public function setLocations(array $locations)
    {
        $this->locations = $locations;
    }
}