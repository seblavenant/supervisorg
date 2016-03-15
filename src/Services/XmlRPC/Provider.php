<?php

namespace Supermonitord\Services\XmlRPC;

use Silex\ServiceProviderInterface;
use Silex\Application;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['xmlrpc.client'] = $app->share(function($c) {
            return new Client($c['configuration']);
        });
    }

    public function boot(Application $app)
    {
    }
}
