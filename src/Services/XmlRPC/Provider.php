<?php

namespace Supervisorg\Services\XmlRPC;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Supervisorg\Services\Process\FilterCollection;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['supervisor.servers'] = $app->share(function($c) {
            $servers = [];

            foreach($c['configuration']->readRequired('supervisor/servers', []) as $serverConfiguration)
            {
                $hostname = $serverConfiguration['host'];
                $host = sprintf('http://%s:%d/RPC2', $hostname, $serverConfiguration['port']);

                $server = new Server(
                    $hostname,
                    $c['supervisor.client.factory']($host),
                    $this->buildServerFilters($serverConfiguration, $c),
                    $c['configuration']
                );

                $servers[$hostname] = $server;
            }

            return $servers;
        });

        $app['supervisor.client.factory'] = $app->protect(function($host) {
            return new CachedClient(
                new Clients\Zend($host)
            );
        });
    }

    public function boot(Application $app)
    {
    }

    private function buildServerFilters(array $configuration, Application $app)
    {
        $filters = new FilterCollection();

        if(array_key_exists('filters', $configuration))
        {
            foreach($configuration['filters'] as $filterName => $filterConfiguration)
            {
                $serviceName = 'process.filter.' . $filterName;
                if( ! $app->offsetExists($serviceName))
                {
                    throw new \LogicException(sprintf('Filter %s not found.', $filterName));
                }

                $filter = call_user_func_array($app[$serviceName], $filterConfiguration);

                $filters->addFilter($filter);
            }
        }

        return $filters;
    }
}
