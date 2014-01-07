<?php

namespace RestRemoteObjectTestAsset\Services;

use RestRemoteObjectTestAsset\Models\Location;
use RestRemoteObjectTestAsset\Options\PaginationOptions;

/**
 * Remote interface
 */
interface UserServiceInterface
{
    /**
     * @rest\http GET
     * @rest\uri /users/%id
     * @param  int                                    $id
     * @return \RestRemoteObjectTestAsset\Models\User
     */
    public function get($id);

    /**
     * @rest\http GET
     * @rest\uri /users?location=%location&offset=%offset&limit=%limit
     * @param  Location                                 $location
     * @param  PaginationOptions                        $pagination
     * @return \RestRemoteObjectTestAsset\Models\User[]
     */
    public function getUsersFromLocation(Location $location, PaginationOptions $pagination);

    /**
     * @rest\http POST
     * @rest\uri /users
     * @param  array                                  $data
     * @return \RestRemoteObjectTestAsset\Models\User
     */
    public function create(array $data);

    /**
     * @rest\http POST
     * @rest\uri /bad
     */
    public function badResource();
}
