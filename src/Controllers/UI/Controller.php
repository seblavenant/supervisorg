<?php

namespace Supervisorg\Controllers\UI;

use Spear\Silex\Application\Traits;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Supervisorg\Domain\ServerCollection;
use Spear\Silex\Provider\Traits\TwigAware;
use Supervisorg\Domain\LogicalGroupCollection;
use Supervisorg\Persistence\UserGroupRepository;

class Controller
{
    use
        TwigAware,
        Traits\RequestAware,
        LoggerAwareTrait;

    private
        $userGroupRepository,
        $servers,
        $logicalGroups;

    public function __construct(ServerCollection $servers, LogicalGroupCollection $logicalGroups, UserGroupRepository $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->servers = $servers;
        $this->logicalGroups = $logicalGroups;
        $this->logger = new NullLogger();
    }

    private function retrieveLogicalGroupsAndValues()
    {
        $logicalGroups = array();

        foreach($this->logicalGroups as $logicalGroup)
        {
            $values = [];

            foreach($this->servers as $server)
            {
                $values = array_merge($values, $server->extractLogicalGroupValues($logicalGroup));
            }

            $values = array_unique($values);
            sort($values);

            $logicalGroups[$logicalGroup->getName()] = array(
                'logicalGroup' => $logicalGroup,
                'values' => $values,
            );
        }

        return $logicalGroups;
    }

    public function sidebarAction()
    {
        return $this->render('layout/sidebar.twig', [
            'currentServer' => $this->request->attributes->get('serverName', null),
            'currentLogicalGroupName' => $this->request->attributes->get('logicalGroupName', null),
            'currentLogicalGroupValue' => $this->request->attributes->get('logicalGroupValue', null),
            'currentUserGroupName' => $this->request->attributes->get('userGroupName', null),

            'servers' => $this->servers,
            'logicalGroups' => $this->retrieveLogicalGroupsAndValues(),
            'userGroups' => $this->userGroupRepository->findAll()
        ]);
    }

}
