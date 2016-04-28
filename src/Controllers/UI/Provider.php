<?php

namespace Supervisorg\Controllers\UI;

use Silex\ControllerProviderInterface;
use Silex\Application;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.ui'] = function() use($app) {
            $controller = new Controller(
                $app['collection.servers'],
                $app['collection.logicalGroups'],
                $app['mongodb']
            );

            $controller->setTwig($app['twig']);
            $controller->setRequest($app['request']);

            return $controller;
        };

        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/sidebar', 'controller.ui:sidebarAction')
            ->method('GET')
            ->bind('sidebar');

        return $controllers;
    }
}
