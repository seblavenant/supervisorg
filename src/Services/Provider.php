<?php

namespace Supervisorg\Services;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Supervisorg\Services\AsynchronousRunners\Amqp;
use Supervisorg\Domain\LogicalGroupCollection;
use Supervisorg\Domain\LogicalGroup;

class Provider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->register(new Processes\Provider());
        $app->register(new XmlRPC\Provider());

        $app['supervisor.processes'] = $app->share(function($c) {
            return new ProcessCollectionProvider(
                $c['collection.servers'],
                $c['collection.logicalGroups'],
                $c['asynchronous.runner'],
                $c['repository.userGroups']
            );
        });

        $app['asynchronous.runner'] = $app->share(function($c) {
            return new Amqp(
                $c['amqp.client'],
                $c['configuration']->read('amqp/supervisorg/exchange', 'supervisorg')
            );
        });

        $app['collection.logicalGroups'] = $app->share(function($c) {
            $logicalGroups = $c['configuration']->read('process/logicalGroups');
            $collection = new LogicalGroupCollection();

            foreach($logicalGroups as $name => $yamlDefinition)
            {
                $collection->add(new LogicalGroup($name, $yamlDefinition));
            }

            return $collection;
        });
    }

    public function boot(Application $app)
    {
    }
}
