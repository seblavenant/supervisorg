<?php

namespace Supervisorg\Persistence;

use Supervisorg\Domain;

interface UserGroupRepository
{
    /**
     * @return Domain\UserGroupCollection
     */
    public function findAll();

    /**
     * @return Domain\UserGroup|null
     */
    public function findOne($userGroupName);
}
