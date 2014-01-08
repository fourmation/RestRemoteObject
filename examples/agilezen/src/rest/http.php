<?php

use Zend\Http\Client as BaseClient;
use Zend\Http\Client\Adapter\Curl as CurlAdapter;

class HttpClient extends BaseClient
{
    public function __construct($uri = null, $options = null)
    {
        $curl = new CurlAdapter();
        $curl->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
        $this->setAdapter($curl);

        parent::__construct($uri, $options);
    }
}