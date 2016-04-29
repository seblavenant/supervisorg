<?php

namespace Supervisorg\Domain;

class UserGroup
{
    private
        $name,
        $label,
        $processNames;

    public function __construct($name, $label, array $processNames)
    {
        $this->name = $name;
        $this->label = $label;
        $this->processNames = $processNames;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getProcessNames()
    {
        return $this->processNames;
    }
}
