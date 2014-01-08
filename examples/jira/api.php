<?php

require __DIR__ . '/../../vendor/autoload.php';

$config = array(
    'host' => '', // here your JIRA uri
    'user' => '', // here your username
    'password' => '', // here your password
    'version' => '2',
);

$username = str_replace('.', '\\u002e', $config['user']);

/** @var \Search $searchService */
$searchService = require __DIR__ . '/objects/services/search.php';
$issues = $searchService->assignee($username); // get your issues

foreach ($issues as $issue) {
    echo "Issue #" . $issue->getId() . "\n";
}