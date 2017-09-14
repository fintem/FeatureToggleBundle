<?php

namespace Fintem\FeatureToggleBundle\Exception;

/**
 * Class DisabledFeatureException.
 */
class DisabledFeatureException extends \Exception
{
    /**
     * @param string $featureName
     *
     * @return DisabledFeatureException
     */
    public static function createByName(string $featureName): DisabledFeatureException
    {
        $message = sprintf('Feature %s is disabled.', $featureName);

        return new self($message);
    }
}
