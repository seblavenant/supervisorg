<?php

namespace Supervisorg\Controllers\UserGroups;

use Silex\ControllerProviderInterface;
use Silex\Application;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.userGroups'] = function() use($app) {
            $controller = new Controller(
                $app['collection.servers'],
                $app['repository.userGroups']
            );

            $controller->setTwig($app['twig']);
            $controller->setRequest($app['request']);

            return $controller;
        };

        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/', 'controller.userGroups:homeAction')
            ->method('GET')
            ->bind('configure_userGroups');

        return $controllers;
    }
}
