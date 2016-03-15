<?php

namespace Supermonitord\Services;

use Silex\ServiceProviderInterface;
use Silex\Application;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->register(new XmlRPC\Provider());
    }

    public function boot(Application $app)
    {
    }
}
