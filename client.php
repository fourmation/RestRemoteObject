<?php

require "vendor/autoload.php";
require "models/Location.php";
require "models/User.php";
require "options/PaginationOptions.php";
require "services/LocationServiceInterface.php";

use Fourmation\ProxyManager\Factory\RemoteObject\Adapter\Rest as RestAdapter;
use Fourmation\ProxyManager\Factory\RemoteObject\Client\Rest as RestClient;

/**
 * Create remote object
 */
$factory = new \ProxyManager\Factory\RemoteObjectFactory(
    new RestAdapter(new RestClient())
);
$remoteLocationService = $factory->createProxy('LocationServiceInterface');



/**
 * Use the REST API in oriented object style
 */
echo "*** 1 :\n";
$remoteLocation = $remoteLocationService->get(1);
var_dump($remoteLocation->getAddress());



echo "*** 2 :\n";
$user = new User();
$user->setId(1);
$pagination = new PaginationOptions(0, 20);
$locations = $remoteLocationService->getAllFromUser($user, $pagination);
foreach($locations as $remoteLocation) {
    var_dump($remoteLocation->getAddress());
}



echo "*** 3 :\n";
$remoteLocation = $remoteLocationService->create(array('Pitt Street', 'Sydney'));
var_dump($remoteLocation->getAddress());