<?php

namespace RestRemoteObjectMock;

use RestRemoteObjectTestAsset\Models\Location;

use Zend\Http\Client as BaseHttpClient;
use Zend\Http\Response;

class HttpClient extends BaseHttpClient
{
    public function send(Request $request = null)
    {
        $uri = $this->getUri();
        $response = new Response();

        if (preg_match('#locations$#', $uri->toString())) {
            $post = $this->getRequest()->getPost()->toArray();
            $location = new Location();
            $location->setAddress($post[0]['address']);

            $content = json_encode(array(
                array(
                    'address' => $location->getAddress(),
                ),
            ));
            $response->setContent($content);
        }

        if (preg_match('#locations\/\d$#', $uri->toString())) {
            $location = new Location();
            $location->setAddress('Pitt Street');

            $content = json_encode(array(
                array(
                    'address' => $location->getAddress(),
                ),
            ));
            $response->setContent($content);
        }

        if (preg_match('#locations\/\?user#', $uri->toString())) {
            $location1 = new Location();
            $location1->setAddress('George Street');

            $location2 = new Location();
            $location2->setAddress('Pitt Street');

            $content = json_encode(array(
                array(
                    'address' => $location1->getAddress(),
                ),
                array(
                    'address' => $location2->getAddress(),
                ),
            ));
            $response->setContent($content);
        };

        return $response;
    }
}