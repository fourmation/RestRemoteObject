<?php

interface ProjectInterface
{
    /**
     * @rest\http GET
     * @rest\uri /projects
     * @return \Project[]
     */
    public function getAll();
}