<?php

namespace Supervisorg\Controllers\Home;

use Silex\ControllerProviderInterface;
use Silex\Application;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.home'] = function() use($app) {
            $controller = new Controller($app['twig'], $app['supervisor.servers']);
            $controller->setRequest($app['request']);
            $controller->setSession($app['session']);
            $controller->setUrlGenerator($app['url_generator']);

            return $controller;
        };

        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/', 'controller.home:homeAction')
            ->method('GET')
            ->bind('home');

        $controllers
            ->match('/servers/{serverName}', 'controller.home:serversAction')
            ->assert('serverName', '[\w-_.]+')
            ->method('GET')
            ->bind('servers');

        $controllers
            ->match('/servers/{serverName}/process/stop/{processName}', 'controller.home:stopProcessAction')
            ->assert('serverName', '[\w-_.]+')
            ->assert('processName', '[\w-_.]+')
            ->method('GET')
            ->bind('process.stop');

        $controllers
            ->match('/servers/{serverName}/process/start/{processName}', 'controller.home:startProcessAction')
            ->assert('serverName', '[\w-_.]+')
            ->assert('processName', '[\w-_.]+')
            ->method('GET')
            ->bind('process.start');

        return $controllers;
    }
}
