<?php

namespace Fintem\FeatureToggleBundle\Service;

/**
 * Interface CacheKeyGeneratorInterface.
 */
interface CacheKeyGeneratorInterface
{
    /**
     * @param string $featureName
     *
     * @return string
     */
    public function generate(string $featureName);
}