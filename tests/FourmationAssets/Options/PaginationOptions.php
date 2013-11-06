<?php

namespace FourmationAssets\Options;

class PaginationOptions
{
    protected $limit;
    protected $offset;

    public function __construct($offset, $limit)
    {
        $this->setOffset($offset);
        $this->setLimit($limit);
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
}