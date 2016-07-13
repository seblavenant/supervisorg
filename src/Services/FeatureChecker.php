<?php

namespace Supervisorg\Services;

use Puzzle\Configuration;

class FeatureChecker
{
    private
        $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function isEnabled($name)
    {
        return $this->configuration->readRequired("features/$name/enabled");
    }
}
