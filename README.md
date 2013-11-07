# Rest Remote Object

This library provide a REST adapter for the Remote Object pattern implemented by the ProxyManager project.
A REST client is also provided to facilitate the REST interaction.

## Rest Adapter examples

A remote object proxy is an object that is located on a different system, but is used as if it was available locally.

To use the REST remote object, add two tags to yours services interfaces : @http to define the HTTP method to use and @uri to define the resource URI :

```php
interface LocationServiceInterface
{
    /**
     * @http GET
     * @uri /locations/%id
     * @param int $id
     * @return \Models\Location
     */
    public function get($id);
}
```

That's all ! You are ready to use your REST API now :

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;

$factory = RemoteObjectFactory(
    new RestAdapter(
        new RestClient('http://my-company.com/rest')
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('LocationServiceInterface');

$location = $proxy->get(1); // The result is automatically converted to a `\Models\Location` class.

var_dump($location->getAddress()); // '28 Foveaux Street'
```

## Rest authentication

You can easily use an authentication with your REST client :

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\AuthenticationQuery\AuthenticationStrategy;

$queryAuth = new AuthenticationStrategy();
$queryAuth->setPublicKey('12345689');
$queryAuth->setPrivateKey('qwerty');

$client = new RestClient('http://my-company.com/rest');
$client->setAuthenticationStrategy($queryAuth);

$factory = new RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('LocationServiceInterface');

$location = $proxy->get(1); // Your request will be `http://my-company.com/rest/locations/1?public_key=12345689&signature=aaa665b46e1060c6b7e5a6b5c891c37312149ece`

var_dump($location->getAddress()); // '28 Foveaux Street'
```

Three authentication strategy are available : HTTP authentication, simple token and the query authentication.

## TODO

* XML response conversion
* Custom response conversion
* Rest client, MethodDescriptor, ResponseHandler & RestParametersAware unit tests
* RestParametersAware documentation
* URI building examples