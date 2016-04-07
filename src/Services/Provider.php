<?php

namespace Supervisorg\Services;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Supervisorg\Services\AsynchronousRunners\Amqp;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->register(new Processes\Provider());
        $app->register(new XmlRPC\Provider());

        $app['supervisor.processes'] = $app->share(function($c) {
            return new ProcessCollectionProvider($c['supervisor.servers'], $c['asynchronous.runner']);
        });

        $app['asynchronous.runner'] = $app->share(function($c) {
            return new Amqp($c['amqp.client']);
        });
    }

    public function boot(Application $app)
    {
    }
}
