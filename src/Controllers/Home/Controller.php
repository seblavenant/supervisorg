<?php

namespace Supervisorg\Controllers\Home;

use Spear\Silex\Application\Traits;
use Supervisorg\Services\ProcessCollectionProvider;
use Spear\Silex\Provider\Traits\TwigAware;

class Controller
{
    use
        TwigAware;

    private
        $processCollectionProvider;

    public function __construct(ProcessCollectionProvider $processCollectionProvider)
    {
        $this->processCollectionProvider = $processCollectionProvider;
    }

    public function homeAction()
    {
        return $this->render('pages/home.twig', [
            'processes' => $this->processCollectionProvider->findAll(),
        ]);
    }

    public function serversAction($serverName)
    {
        return $this->render('pages/servers.twig', [
            'currentServer' => $serverName,
            'processes' => $this->processCollectionProvider->findByServerName($serverName),
        ]);
    }

    public function logicalGroupsAction($logicalGroupName, $logicalGroupValue)
    {
        return $this->render('pages/logicalGroup.twig', [
            'currentLogicalGroupName' => $logicalGroupName,
            'currentLogicalGroupValue' => $logicalGroupValue,
            'processes' => $this->processCollectionProvider->findByLogicalGroup($logicalGroupName, $logicalGroupValue),
        ]);
    }
}
