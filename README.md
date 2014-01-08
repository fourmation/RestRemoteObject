# Rest Remote Object

This library provide a REST adapter for the Remote Object pattern implemented by the ProxyManager project.
A REST client is also provided to facilitate the REST interaction.

## Rest remote objects examples

Some examples are provided in the `examples/` directory : JIRA, ZenDesk and FlightStats !
Now, you can transform all REST API in remote objects !

## Rest Adapter usages

A remote object proxy is an object that is located on a different system, but is used as if it was available locally.

To use the REST remote object, add two tags to yours services interfaces : @rest\http to define the HTTP method to use and @uri to define the resource URI :

```php
interface UserServiceInterface
{
    /**
     * @rest\http GET
     * @rest\uri /users/%id
     * @param int $id
     * @return \Models\User
     */
    public function get($id);
}
```

That's all ! You are ready to use your REST API now :

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;

$client = new RestClient('http://my-company.com/rest');
$client->setFormatStrategy(new HeaderFormatStrategy(new Format(Format::JSON)));

$factory = RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('UserServiceInterface');

$user = $proxy->get(1); // The result is automatically converted to a `\Models\User` class.

var_dump($user->getName()); // 'Vincent'
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
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;
use RestRemoteObject\Client\Rest\Versioning\HeaderVersioningStrategy;

$versioning = new HeaderVersioningStrategy('3.0');

$client = new RestClient('http://my-company.com/rest');
$client->setFormatStrategy(new HeaderFormatStrategy(new Format(Format::JSON)));
$client->setVersioningStrategy($versioning);

$factory = new RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('UserServiceInterface');

$user = $proxy->get(1); // A header "Rest-Version: v3" will be added

var_dump($user->getName()); // 'Vincent'
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
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;

$queryAuth = new QueryAuthenticationStrategy();
$queryAuth->setPublicKey('12345689');
$queryAuth->setPrivateKey('qwerty');

$client = new RestClient('http://my-company.com/rest');
$client->setFormatStrategy(new HeaderFormatStrategy(new Format(Format::JSON)));
$client->setAuthenticationStrategy($queryAuth);

$factory = new RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('UserServiceInterface');

$user = $proxy->get(1); // Your request will be `http://my-company.com/rest/users/1?public_key=12345689&signature=aaa665b46e1060c6b7e5a6b5c891c37312149ece`

var_dump($user->getName()); // 'Vincent'
```

## Feature

To apply feature on your API request, just implement the FeatureInterface and use the `addFeature` method. Example with the timestamp feature :

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Feature\Timestamp;
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;

$queryAuth = new QueryAuthenticationStrategy();
$queryAuth->setPublicKey('12345689');
$queryAuth->setPrivateKey('qwerty');

$client = new RestClient('http://my-company.com/rest');
$client->setFormatStrategy(new HeaderFormatStrategy(new Format(Format::JSON)));
$client->addFeature(new TimestampFeature());

$factory = new RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('UserServiceInterface');

$user = $proxy->get(1); // Your request will be `http://my-company.com/rest/locations/1?t=1383881696`

var_dump($user->getName()); // 'Vincent'
```

## Custom response parser

Two response parser are provided : XML and JSON. The parser is selected automatically based on the response format.
You can write your own parser, just implements the `RestRemoteObject\Client\Rest\ResponseHandler\Parser\ParserInterface` interface :

```php
interface ParserInterface
{
    /**
     * Parse response content
     *
     * @param $content
     * @return array
     */
    public function parse($content);
}
```

Use your parser like this :

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;

$client = new RestClient('http://my-company.com/rest');
$client->setFormatStrategy(new HeaderFormatStrategy(new Format(Format::JSON)));

$responseHandler = $client->getResponseHandler();
$responseHandler->getResponseParser(new MyParser()); // create your own logic here

$factory = new RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('UserServiceInterface');

$user = $proxy->get(1);

var_dump($user->getName()); // 'Vincent'
```

## Custom response builder

Two standards builder are provided : DefaultBuilder and GhostObjectBuilder.

The DefaultBuilder transform data (provide by the response parser) to an object. By default, the `ClassMethods` hydrator
will use, so if you have defined your getter/setter, the object will be built easily.

The second builder is the GhostObjectBuilder which provide a proxy object (by setter/getter such as the DefaultBuilder)
instead the real object, and allow remote call from uninitialized properties :

```php
use ProxyManager\Factory\RemoteObjectFactory;
use RestRemoteObject\Adapter\Rest as RestAdapter;
use RestRemoteObject\Client\Rest as RestClient;
use RestRemoteObject\Client\Rest\Format\Format;
use RestRemoteObject\Client\Rest\Format\HeaderFormatStrategy;

$client = new RestClient('http://my-company.com/rest');
$client->setFormatStrategy(new HeaderFormatStrategy(new Format(Format::JSON)));

$responseHandler = $client->getResponseHandler();
$responseHandler->setResponseBuilder(new GhostObjectBuilder($this->restClient));

$factory = new RemoteObjectFactory(
    new RestAdapter(
        $client
    )
);

// proxy is your remote implementation
$proxy = $factory->createProxy('UserServiceInterface');

$user = $proxy->get(1);

var_dump($user->getName()); // 'Vincent' -- local data
var_dump($user->getLocations()); // will call remote method !
```

To have remote call, just define your annotations in your model :

```php
class User
{
    public function getId()
    {
        return $this->id;
    }

    /**
     * @rest\http GET
     * @rest\uri /locations?user=:getId
     * @rest\mapping setLocations
     * @return \RestRemoteObjectTestAsset\Models\Location[]
     */
    public function getLocations()
    {
        return $this->locations;
    }

    public function setLocations(array $locations)
    {
        $this->locations = $locations;
    }
}
```
