<?php

namespace RestRemoteObjectMock;

use RestRemoteObjectTestAsset\Models\Location;

use RestRemoteObjectTestAsset\Models\User;
use Zend\Http\Client as BaseHttpClient;
use Zend\Http\Response;
use Zend\Http\Request;

class HttpClient extends BaseHttpClient
{
    public function send(Request $request = null)
    {
        if (!$request) {
            $request = $this->getRequest();
        }
        $this->lastRawRequest = $request->toString();

        $uri = $this->getUri();
        $response = new Response();

        if ($request->getMethod() == 'POST' && preg_match('#users#', $uri->toString())) {
            $post = $this->getRequest()->getPost()->toArray();
            $user = new User();
            $user->setName($post[0]['name']);

            $content = json_encode(array(
                array(
                    'name' => $user->getName(),
                ),
            ));
            $response->setContent($content);
        }

        else if (preg_match('#users\/\d$#', $uri->toString())) {
            $user = new User();
            $user->setName('Vincent');

            $content = json_encode(array(
                array(
                    'name' => $user->getName(),
                ),
            ));
            $response->setContent($content);
        }

        else if (preg_match('#users#', $uri->toString())) {
            $user1 = new User();
            $user1->setName('Vincent');

            $user2 = new User();
            $user2->setName('Dave');

            $content = json_encode(array(
                array(
                    'name' => $user1->getName(),
                ),
                array(
                    'name' => $user2->getName(),
                ),
            ));
            $response->setContent($content);
        }

        else {
            throw new \RuntimeException('Http mock routing error : ' . $uri->toString());
        }

        return $response;
    }
}