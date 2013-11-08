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

## Rest versioning

This project offer three way for the versioning :
* versioning included in a header (recommended)
* versioning included in URL
* versioning included in URL parameter

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Versioning\HeaderVersioningStrategy;

$versioning = new HeaderVersioningStrategy('3.0', 'json');

$client = new RestClient('http://my-company.com/rest');
$client->setVersioningStrategy($versioning);

$factory = new RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('LocationServiceInterface');

$location = $proxy->get(1); // A header "Rest-Version: v3+json" will be added

var_dump($location->getAddress()); // '28 Foveaux Street'
```

## Rest authentication

Three authentication strategy are available :
* Query authentication (recommended)
* HTTP authentication
* simple token

You can easily use an authentication with your REST client :

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Authentication\QueryAuthenticationStrategy;

$queryAuth = new QueryAuthenticationStrategy();
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

## Feature

To apply feature on your API request, just implement the FeatureInterface and use the `addFeature` method. Example with the timestamp feature :

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Feature\Timestamp;

$queryAuth = new QueryAuthenticationStrategy();
$queryAuth->setPublicKey('12345689');
$queryAuth->setPrivateKey('qwerty');

$client = new RestClient('http://my-company.com/rest');
$client->addFeature(new TimestampFeature());

$factory = new RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('LocationServiceInterface');

$location = $proxy->get(1); // Your request will be `http://my-company.com/rest/locations/1?t=1383881696`

var_dump($location->getAddress()); // '28 Foveaux Street'
```

## TODO

* XML response conversion
* Rest client, MethodDescriptor, ResponseHandler & RestParametersAware unit tests
* RestParametersAware documentation
* URI building examples