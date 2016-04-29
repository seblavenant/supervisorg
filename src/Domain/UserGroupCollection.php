<?php

namespace Supervisorg\Domain;

class UserGroupCollection implements \IteratorAggregate, Collection
{
    private
        $groups;

    public function __construct()
    {
        $this->groups = array();
    }

    public function add(UserGroup $group)
    {
        $this->groups[$group->getName()] = $group;

        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->groups);
    }

    /**
     * @return Server
     */
    public function getByName($userGroupName)
    {
        if(isset($this->groups[$userGroupName]))
        {
            return $this->groups[$userGroupName];
        }

        throw new \RuntimeException("User group $userGroupName not found");
    }

    public function count()
    {
        return iterator_count($this);
    }
}
