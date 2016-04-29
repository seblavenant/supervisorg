<?php

namespace Supervisorg\Persistence;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Supervisorg\Persistence\Repositories\UserGroup;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mongodb'] = $app->share(function($c) {

            $dsn = sprintf(
                'mongodb://%s:%s',
                $c['configuration']->readRequired('mongodb/server/host'),
                $c['configuration']->readRequired('mongodb/server/port')
            );

            $client = new \MongoDB\Client($dsn);

            return $client->selectDatabase($c['configuration']->readRequired('mongodb/database'));
        });


        $app['repository.userGroups'] = $app->share(function($c) {
            return new UserGroup(
                $c['mongodb']
            );
        });
    }

    public function boot(Application $app)
    {
    }
}
