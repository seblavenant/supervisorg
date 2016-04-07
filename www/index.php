<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Puzzle\Configuration\Yaml;
use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local;
use Supervisorg\Application;
use Puzzle\Configuration\Stacked;

$configurationFilesStorage = new Filesystem(new Local(__DIR__ . '/../config/built-in'));
$configurationBuiltIn = new Yaml($configurationFilesStorage);

$configurationFilesStorage = new Filesystem(new Local(__DIR__ . '/../config/local'));
$configurationLocal = new Yaml($configurationFilesStorage);

$configuration = new Stacked();
$configuration->overrideBy($configurationBuiltIn)
    ->overrideBy($configurationLocal);

$rootDir = realpath(__DIR__ . '/..');

$app = new Application($configuration, $rootDir);

$app->run();
