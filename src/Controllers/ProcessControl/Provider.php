<?php

namespace Supervisorg\Controllers\ProcessControl;

use Silex\ControllerProviderInterface;
use Silex\Application;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.processControl'] = function() use($app) {
            $controller = new Controller(
                $app['supervisor.processes']
            );

            $controller->setRequest($app['request']);
            $controller->setSession($app['session']);
            $controller->setUrlGenerator($app['url_generator']);

            return $controller;
        };

        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/servers/{serverName}/process/start/{processName}', 'controller.processControl:startProcessAction')
            ->assert('serverName', '[\w-_.]+')
            ->assert('processName', '[\w-_.]+')
            ->method('GET')
            ->bind('process.start');

        $controllers
            ->match('/servers/{serverName}/process/stop/{processName}', 'controller.processControl:stopProcessAction')
            ->assert('serverName', '[\w-_.]+')
            ->assert('processName', '[\w-_.]+')
            ->method('GET')
            ->bind('process.stop');

        $controllers
            ->match('/process/startAll', 'controller.processControl:startAllAction')
            ->method('GET')
            ->bind('process.startAll');

        $controllers
            ->match('/process/stopAll', 'controller.processControl:stopAllAction')
            ->method('GET')
            ->bind('process.stopAll');

        $controllers
            ->match('/servers/{serverName}/process/startAll', 'controller.processControl:serverStartAllAction')
            ->assert('serverName', '[\w-_.]+')
            ->method('GET')
            ->bind('process.server.startAll');

        $controllers
            ->match('/servers/{serverName}/process/stopAll', 'controller.processControl:serverStopAllAction')
            ->assert('serverName', '[\w-_.]+')
            ->method('GET')
            ->bind('process.server.stopAll');

        $controllers
            ->match('/logical-groups/{logicalGroupName}/{logicalGroupValue}/process/startAll', 'controller.processControl:logicalGroupStartAllAction')
            ->assert('logicalGroupName', '[\w-_.]+')
            ->assert('logicalGroupValue', '[\w-_.]+')
            ->method('GET')
            ->bind('process.logicalGroup.startAll');

        $controllers
            ->match('/logical-groups/{logicalGroupName}/{logicalGroupValue}/process/stopAll', 'controller.processControl:logicalGroupStopAllAction')
            ->assert('logicalGroupName', '[\w-_.]+')
            ->assert('logicalGroupValue', '[\w-_.]+')
            ->method('GET')
            ->bind('process.logicalGroup.stopAll');

        return $controllers;
    }
}
