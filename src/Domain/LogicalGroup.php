<?php

namespace Supervisorg\Domain;

class LogicalGroup
{
    private
        $name,
        $icon,
        $regex;

    public function __construct($name, array $definition)
    {
        $this->name = $name;

        $definition = $this->ensureDefinitionIsValid($definition);

        $this->regex = sprintf('~%s~', $definition['regex']);
        $this->icon = $definition['icon'];
    }

    private function ensureDefinitionIsValid(array $definition)
    {
        $defaultValues = [
            'defaultView' => false,
            'icon' => 'circle-o-notch',
        ];

        $definition = $definition + $defaultValues;

        $requiredFields = ['regex'];
        foreach($requiredFields as $field)
        {
            if(! isset($definition[$field]))
            {
                throw new \InvalidArgumentException(sprintf(
                    'Missing field %s in logical group %s definition',
                    $field,
                    $this->name
                ));
            }
        }

        return $definition;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function belongTo(Process $process, $value)
    {
        if($this->belongToAny($process))
        {
            return $this->getValue($process) === $value;
        }

        return false;
    }

    public function belongToAny(Process $process)
    {
        return preg_match($this->regex, $process->getName());
    }

    public function getValue(Process $process)
    {
        $value = null;

        if(preg_match($this->regex, $process->getName(), $matches))
        {
            if(isset($matches['groupValue']))
            {
                $value = $matches['groupValue'];
            }
        }

        return $value;
    }
}
