<?php

namespace Supervisorg\Controllers\Home;

use Spear\Silex\Application\Traits;
use Supervisorg\Services\ProcessCollectionProvider;
use Spear\Silex\Provider\Traits\TwigAware;
use Supervisorg\Domain\LogicalGroupCollection;

class Controller
{
    use
        TwigAware;

    private
        $logicalGroups,
        $processCollectionProvider;

    public function __construct(ProcessCollectionProvider $processCollectionProvider, LogicalGroupCollection $logicalGroups)
    {
        $this->processCollectionProvider = $processCollectionProvider;
        $this->logicalGroups = $logicalGroups;
    }

    public function homeAction()
    {
        return $this->render('pages/home.twig', [
            'processes' => $this->processCollectionProvider->findAll(),
            'currentLogicalGroup' => $this->logicalGroups->getDefault(),
        ]);
    }

    public function serversAction($serverName)
    {
        return $this->render('pages/servers.twig', [
            'processes' => $this->processCollectionProvider->findByServerName($serverName),
            'currentServer' => $serverName,
            'currentLogicalGroup' => $this->logicalGroups->getDefault(),
        ]);
    }

    public function logicalGroupsAction($logicalGroupName, $logicalGroupValue)
    {
        return $this->render('pages/logicalGroup.twig', [
            'processes' => $this->processCollectionProvider->findByLogicalGroup($logicalGroupName, $logicalGroupValue),
            'currentLogicalGroup' => $this->logicalGroups->getByName($logicalGroupName),
            'currentLogicalGroupValue' => $logicalGroupValue,
        ]);
    }

    public function userGroupsAction($userGroupName)
    {
        return $this->render('pages/userGroup.twig', [
            'processes' => $this->processCollectionProvider->findByUserGroup($userGroupName),
            'currentUserGroup' => $userGroupName,
            'currentLogicalGroup' => $this->logicalGroups->getDefault(),
        ]);
    }
}
