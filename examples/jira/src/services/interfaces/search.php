<?php

interface SearchInterface
{
    /**
     * @rest\http GET
     * @rest\uri /search/?jql=assignee=%name
     * @param $name
     * @return \Issue[]
     */
    public function assignee($name);
}