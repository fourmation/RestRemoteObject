<?php

namespace FourmationAssets\Services;

use FourmationAssets\Options\PaginationOptions;
use FourmationAssets\Models\User;

/**
 * Remote interface
 */
interface LocationServiceInterface
{
    /**
     * @http GET
     * @uri http://localhost/locations/%id
     * @param int $id
     * @return \FourmationAssets\Models\Location
     */
    public function get($id);

    /**
     * @http GET
     * @uri http://localhost/locations/?user=%user&offset=%offset&limit=%limit
     * @param User $user
     * @param PaginationOptions $pagination
     * @return \FourmationAssets\Models\Location[]
     */
    public function getAllFromUser(User $user, PaginationOptions $pagination);

    /**
     * @http POST
     * @uri http://localhost/locations
     * @param array $data
     * @return \FourmationAssets\Models\Location
     */
    public function create(array $data);
}