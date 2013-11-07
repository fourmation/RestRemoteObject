<?php

namespace RestRemoteObjectTestAsset\Services;

use RestRemoteObjectTestAsset\Options\PaginationOptions;
use RestRemoteObjectTestAsset\Models\User;

/**
 * Remote interface
 */
interface LocationServiceInterface
{
    /**
     * @http GET
     * @uri /locations/%id
     * @param int $id
     * @return \RestRemoteObjectTestAsset\Models\Location
     */
    public function get($id);

    /**
     * @http GET
     * @uri /locations/?user=%user&offset=%offset&limit=%limit
     * @param User $user
     * @param PaginationOptions $pagination
     * @return \RestRemoteObjectTestAsset\Models\Location[]
     */
    public function getAllFromUser(User $user, PaginationOptions $pagination);

    /**
     * @http POST
     * @uri /locations
     * @param array $data
     * @return \RestRemoteObjectTestAsset\Models\Location
     */
    public function create(array $data);
}