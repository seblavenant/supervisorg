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

    public function stopProcessAction($server, $process)
    {
        if(empty($process))
        {
            $this->addErrorFlash('Empty process name !');

            return $this->redirect('home');
        }

        if( ! array_key_exists($server, $this->servers))
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $return = $this->servers[$server]->stopProcess($process);

        if(! $return)
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $this->addInfoFlash(sprintf('Process %s stopping', $process));

        return $this->redirect('home');
    }

    public function startProcessAction($server, $process)
    {
        if(empty($process))
        {
            $this->addErrorFlash('Empty process name !');

            return $this->redirect('home');
        }

        if( ! array_key_exists($server, $this->servers))
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $server = $this->servers[$server];
        $return = $server->startProcess($process);

        if(! $return)
        {
            $this->addErrorFlash('Error while trying to start process.');

            return $this->redirect('home');
        }

        $this->addInfoFlash(sprintf('Process %s starting', $process));

        return $this->redirect('home');
    }
}
