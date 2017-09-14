<?php

namespace Fintem\FeatureToggleBundle\Exception;

/**
 * Class FeatureNotFoundException.
 */
class FeatureNotFoundException extends \Exception
{
    /**
     * @param string $featureName
     *
     * @return FeatureNotFoundException
     */
    public static function createByName(string $featureName): FeatureNotFoundException
    {
        $message = sprintf('Feature %s not found.', $featureName);

        return new self($message);
    }
}
