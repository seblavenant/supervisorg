<?php

namespace Supervisorg\Controllers\Home;

use Spear\Silex\Application\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supervisorg\Constants\ProcessState;

class Controller
{
    use
        Traits\RequestAware,
        Traits\SessionAware,
        Traits\UrlGeneratorAware,
        LoggerAwareTrait;

    private
        $twig,
        $servers;

    public function __construct(\Twig_Environment $twig, array $servers)
    {
        $this->twig = $twig;
        $this->servers = $servers;

        $this->logger = new NullLogger();
    }

    public function homeAction()
    {
        $processes = [];
        foreach($this->servers as $server)
        {
            $processes = array_merge($processes, $server->getProcessList());
        }

        return $this->twig->render('home.twig', [
            'servers' => $this->servers,
            'processes' => $processes,
        ]);
    }

    public function serversAction($serverName)
    {
        $server = $this->servers[$serverName];

        return $this->twig->render('home.twig', [
            'servers' => $this->servers,
            'processes' => $server->getProcessList(),
        ]);
    }

    public function stopProcessAction($serverName, $processName)
    {
        if(empty($processName))
        {
            $this->addErrorFlash('Empty process name !');

            return $this->redirect('home');
        }

        if( ! array_key_exists($serverName, $this->servers))
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $return = $this->servers[$serverName]->stopProcess($processName);

        if(! $return)
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $this->addInfoFlash(sprintf('Process %s stopping', $processName));

        return $this->redirect('home');
    }

    public function startProcessAction($serverName, $processName)
    {
        if(empty($processName))
        {
            $this->addErrorFlash('Empty process name !');

            return $this->redirect('home');
        }

        if( ! array_key_exists($serverName, $this->servers))
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $server = $this->servers[$serverName];
        $return = $server->startProcess($processName);

        if(! $return)
        {
            $this->addErrorFlash('Error while trying to start process.');

            return $this->redirect('home');
        }

        $this->addInfoFlash(sprintf('Process %s starting', $processName));

        return $this->redirect('home');
    }
}
