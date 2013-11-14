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
     * @http GET
     * @uri /users/%id
     * @param int $id
     * @return \RestRemoteObjectTestAsset\Models\User
     */
    public function get($id);

    /**
     * @http GET
     * @uri /users?location=%location&offset=%offset&limit=%limit
     * @param Location $location
     * @param PaginationOptions $pagination
     * @return \RestRemoteObjectTestAsset\Models\User
     */
    public function getUsersFromLocation(Location $location, PaginationOptions $pagination);

    /**
     * @http POST
     * @uri /users
     * @param array $data
     * @return \RestRemoteObjectTestAsset\Models\User
     */
    public function create(array $data);
}