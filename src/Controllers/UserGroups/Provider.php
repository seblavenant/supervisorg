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
            $controller->setUrlGenerator($app['url_generator']);
            $controller->setSession($app['session']);

            return $controller;
        };

        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/', 'controller.userGroups:homeAction')
            ->method('GET')
            ->bind('configure_userGroups');

        $controllers
            ->match('/edit/{userGroupName}', 'controller.userGroups:editAction')
            ->assert('userGroupName', '[\w-_.]+')
            ->method('GET')
            ->bind('userGroups_edit');

        $controllers
            ->match('/delete/{userGroupName}', 'controller.userGroups:deleteAction')
            ->assert('userGroupName', '[\w-_.]+')
            ->method('GET')
            ->bind('userGroups_delete');

        return $controllers;
    }
}
