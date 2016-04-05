<?php

namespace Supervisorg\Services;

use Silex\ServiceProviderInterface;
use Silex\Application;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->register(new Processes\Provider());
        $app->register(new XmlRPC\Provider());

        $app['supervisor.processes'] = $app->share(function($c) {
            return new ProcessCollectionProvider($c['supervisor.servers']);
        });
    }

    public function boot(Application $app)
    {
    }
}
