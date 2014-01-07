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
            $user->setId(1);
            $user->setName($post[0]['name']);

            $content = json_encode(
                array(
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                )
            );
            $response->setContent($content);
        } else {
            if (preg_match('#users\/\d$#', $uri->toString())) {
                $user = new User();
                $user->setId(1);
                $user->setName('Vincent');

                $content = json_encode(
                    array(
                        'id' => $user->getId(),
                        'name' => $user->getName(),
                    )
                );
                $response->setContent($content);
            } else {
                if (preg_match('#users#', $uri->toString())) {
                    $user1 = new User();
                    $user1->setName('Vincent');

                    $user2 = new User();
                    $user2->setName('Dave');

                    $content = json_encode(
                        array(
                            array(
                                'name' => $user1->getName(),
                            ),
                            array(
                                'name' => $user2->getName(),
                            ),
                        )
                    );
                    $response->setContent($content);
                } else {
                    if (preg_match('#locations#', $uri->toString())) {
                        $location1 = new Location();
                        $location1->setId(1);
                        $location1->setAddress('Pitt Street');

                        $content = json_encode(
                            array(
                                'address' => $location1->getAddress(),
                            )
                        );
                        $response->setContent($content);
                    } else {
                        $response->setStatusCode(404);
                    }
                }
            }
        }

        return $response;
    }
}
