<?php

namespace Supervisorg\Controllers\Home;

use Silex\ControllerProviderInterface;
use Silex\Application;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.home'] = function() use($app) {
            $controller = new Controller(
                $app['supervisor.processes'],
                $app['collection.logicalGroups']
            );

            $controller->setTwig($app['twig']);

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
            ->match('/logical-groups/{logicalGroupName}/{logicalGroupValue}', 'controller.home:logicalGroupsAction')
            ->assert('logicalGroupName', '[\w-_.]+')
            ->assert('logicalGroupValue', '[\w-_.]+')
            ->method('GET')
            ->bind('logicalGroups');

        $controllers
            ->match('/user-groups/{userGroupName}', 'controller.home:userGroupsAction')
            ->assert('userGroupName', '[\w-_.]+')
            ->method('GET')
            ->bind('userGroups');

        return $controllers;
    }
}
