<?php

namespace Fintem\FeatureToggleBundle\Event;

/**
 * Class FeatureValidateEvent.
 */
class FeatureValidateEvent extends FeatureAwareEvent
{
    /**
     * @var bool
     */
    private $valid = true;

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @return $this
     */
    public function setValid()
    {
        $this->valid = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function setInvalid()
    {
        $this->valid = false;
        $this->stopPropagation();

        return $this;
    }
}
