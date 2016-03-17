<?php

namespace Supervisorg\Services\Process;

use Silex\ServiceProviderInterface;
use Silex\Application;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['process.filter.name'] = $app->share(function($c) {
            return new Filters\Name($c['configuration']->read('filter/process/names', []));
        });
    }

    public function boot(Application $app)
    {
    }
}
