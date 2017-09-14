<?php

namespace Fintem\FeatureToggleBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Fintem\FeatureToggleBundle\Entity\Feature;

/**
 * Class FeatureAwareEvent.
 */
class FeatureAwareEvent extends Event
{
    /**
     * @var Feature
     */
    private $feature;

    /**
     * FeatureAwareEvent constructor.
     *
     * @param Feature $feature
     */
    public function __construct(Feature $feature)
    {
        $this->feature = $feature;
    }

    /**
     * @return Feature
     */
    public function getFeature(): Feature
    {
        return $this->feature;
    }
}
