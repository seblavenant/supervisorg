<?php

namespace Supermonitord\Services\XmlRPC\Clients;

use Supermonitord\Services\XmlRPC\Client;
use Puzzle\Configuration;

class Zend implements Client
{
    private
        $client;

    public function __construct(Configuration $configuration)
    {
        $this->client = new \Zend\XmlRpc\Client($configuration->readRequired('xmlrpc/host'));
    }

    public function getProcessList()
    {
        return $this->client->call('supervisor.getAllProcessInfo');
    }

    public function stopProcess($process)
    {
        return $this->client->call('supervisor.stopProcess', [$process]);
    }

    public function startProcess($process)
    {
        return $this->client->call('supervisor.startProcess', [$process]);
    }

    public function readStdErr($process)
    {
        return $this->client->call('supervisor.readProcessStderrLog', array($process, -1000, 0));
    }

    /*
     * FIXME : TMP for dev
     */
    public function getHelp()
    {
        $methods = $this->client->call('system.listMethods');

        $help = '';
        foreach($methods as $method)
        {
            $help .= '<dl>';
                $help .= '<dt><strong>' . $method . '</strong></dt>';
                $help .= '<dd><pre>' . $this->client->call('system.methodHelp', [$method]) . '</pre></dd>';
            $help .= '</dl>';
        }

        return $help;
    }
}
