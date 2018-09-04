<?php

namespace Fintem\FeatureToggleBundle\Constant;

/**
 * Class FeatureToggleEvents.
 */
final class FeatureToggleEvents
{
    const PRE_VALIDATE = 'feature_toggle.validate';
    const PRE_ENABLE = 'feature_toggle.pre_enable';
    const PRE_DISABLE = 'feature_toggle.pre_disable';

    /**
     * @param string $featureName
     *
     * @return string
     */
    public static function getPreValidateEventName(string $featureName): string
    {
        return sprintf('%s.%s', self::PRE_VALIDATE, $featureName);
    }
}
