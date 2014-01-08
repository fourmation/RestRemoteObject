<?php

require __DIR__ . '/../../vendor/autoload.php';

$config = array(
    'token' => '', // here your token
    'version' => 'v1',
);

/** @var \ProjectInterface $projectService */
$projectService = require __DIR__ . '/src/services/project.php';
$projects = $projectService->getAll(); // get your projects

foreach ($projects as $project) {
    echo "Project #" . $project->getId() . ", " . $project->getName() . "\n";
}