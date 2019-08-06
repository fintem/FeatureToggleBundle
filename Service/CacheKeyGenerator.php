<?php

namespace Fintem\FeatureToggleBundle\Service;

/**
 * Class CacheKeyGenerator.
 */
class CacheKeyGenerator implements CacheKeyGeneratorInterface
{
    const PREFIX = 'feature_toggle_bundle_';

    /**
     * {@inheritdoc}
     */
    public function generate(string $featureName)
    {
        return sprintf('%s_%s', static::PREFIX, $featureName);
    }
}