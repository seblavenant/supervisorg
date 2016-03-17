<?php

namespace Supervisorg\Services;

use Silex\ServiceProviderInterface;
use Silex\Application;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->register(new Process\Provider());
        $app->register(new XmlRPC\Provider());
    }

    public function boot(Application $app)
    {
    }
}
