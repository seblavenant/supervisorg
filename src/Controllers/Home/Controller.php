<?php

namespace Supermonitord\Controllers\Home;

use Spear\Silex\Application\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supermonitord\Services\XmlRPC\Client;

class Controller
{
    use
        Traits\RequestAware,
        Traits\SessionAware,
        Traits\UrlGeneratorAware,
        LoggerAwareTrait;

    private
        $twig,
        $client;

    public function __construct(\Twig_Environment $twig, Client $client)
    {
        $this->twig = $twig;
        $this->client = $client;

        $this->logger = new NullLogger();
    }

    public function homeAction()
    {
        $processList = $this->client->getProcessList();

        return $this->twig->render('home.html.twig', ['processList' => $processList]);
    }

    public function helpAction()
    {
        $help = $this->client->getHelp();

        return $this->twig->render('help.html.twig', ['help' => $help]);
    }

    public function stopProcessAction($process)
    {
        if(empty($process))
        {
            $this->addErrorFlash('Empty process name !');

            return $this->redirect('home');
        }

        $return = $this->client->stopProcess($process);

        if(! $return)
        {
            $this->addErrorFlash('Error while trying to stop process.');

            return $this->redirect('home');
        }

        $this->addInfoFlash(sprintf('Process %s stopping', $process));

        return $this->redirect('home');
    }

    public function startProcessAction($process)
    {
        if(empty($process))
        {
            $this->addErrorFlash('Empty process name !');

            return $this->redirect('home');
        }

        $return = $this->client->startProcess($process);

        if(! $return)
        {
            $this->addErrorFlash('Error while trying to start process.');

            return $this->redirect('home');
        }

        $this->addInfoFlash(sprintf('Process %s starting', $process));

        return $this->redirect('home');
    }
}
