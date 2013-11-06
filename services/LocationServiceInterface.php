<?php

/**
 * Remote interface
 */
interface LocationServiceInterface
{
    /**
     * @http GET
     * @uri http://localhost/locations/%id
     * @param int $id
     * @return Location
     */
    public function get($id);

    /**
     * @http GET
     * @uri http://localhost/locations/?user=%user&offset=%offset&limit=%limit
     * @param User $user
     * @param PaginationOptions $pagination
     * @return Location[]
     */
    public function getAllFromUser(User $user, PaginationOptions $pagination);

    /**
     * @http POST
     * @uri http://localhost/locations
     * @param array $data
     * @return Location
     */
    public function create(array $data);
}