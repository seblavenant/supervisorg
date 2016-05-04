<?php

namespace Supervisorg\Controllers\UserGroups;

use Spear\Silex\Application\Traits;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Spear\Silex\Provider\Traits\TwigAware;
use Supervisorg\Persistence\UserGroupRepository;
use Supervisorg\Domain\UserGroup;
use Supervisorg\Services\ProcessCollectionProvider;
use Supervisorg\Domain\LogicalGroupCollection;

class Controller
{
    use
        TwigAware,
        LoggerAwareTrait,
        Traits\RequestAware,
        Traits\SessionAware,
        Traits\UrlGeneratorAware;

    private
        $processCollectionProvider,
        $logicalGroups,
        $userGroupRepository;

    public function __construct(ProcessCollectionProvider $processCollectionProvider, LogicalGroupCollection $logicalGroups, UserGroupRepository $userGroupRepository)
    {
        $this->processCollectionProvider = $processCollectionProvider;
        $this->logicalGroups = $logicalGroups;
        $this->userGroupRepository = $userGroupRepository;

        $this->logger = new NullLogger();
    }

    public function configureAction()
    {
        return $this->render('pages/configure/userGroups.twig', [
            'userGroups' => $this->userGroupRepository->findAll(),
        ]);
    }

    public function newAction()
    {
        return $this->render('pages/configure/editUserGroups.twig', [
            'processes' => $this->processCollectionProvider->findAll(),
            'currentLogicalGroup' => $this->logicalGroups->getDefault(),
            'userGroup' => null,
        ]);
    }

    public function editAction($userGroupName)
    {
        $userGroup = $this->userGroupRepository->findOne($userGroupName);

        if(! $userGroup instanceof UserGroup)
        {
            $this->addErrorFlash("User group ($userGroupName) not found");

            return $this->redirect('configure_userGroups');
        }

        return $this->render('pages/configure/editUserGroups.twig', [
            'processes' => $this->processCollectionProvider->findAll(),
            'currentLogicalGroup' => $this->logicalGroups->getDefault(),
            'userGroup' => $userGroup,
        ]);
    }

    public function saveAction()
    {
        // FIXME not implemented yet

        return $this->redirect('configure_userGroups');
    }

    public function deleteAction($userGroupName)
    {
        $success = $this->userGroupRepository->delete($userGroupName);

        $this->addResultFlash($success, "User group ($userGroupName) has been successfully deleted", "Error while deleting user group ($userGroupName)");

        return $this->redirect('configure_userGroups');
    }
}
