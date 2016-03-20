<?php

namespace Supervisorg\Services\XmlRPC;

use Silex\ServiceProviderInterface;
use Silex\Application;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['supervisor.servers'] = $app->share(function($c) {
            $servers = [];

            foreach($c['configuration']->readRequired('xmlrpc/servers', []) as $serverConfiguration)
            {
                $hostname = $serverConfiguration['host'];
                $host = sprintf('http://%s:%d/RPC2', $hostname, $serverConfiguration['port']);

                $server = new Server($hostname, $c['supervisor.client.factory']($host));

                $servers[$hostname] = $server;
            }

            return $servers;
        });

        $app['supervisor.client.factory'] = $app->protect(function($host) {
            return new Clients\Zend($host);
        });
    }

    public function boot(Application $app)
    {
    }
}
