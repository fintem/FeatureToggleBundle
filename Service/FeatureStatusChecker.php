<?php

namespace Fintem\FeatureToggleBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Fintem\FeatureToggleBundle\Constant\FeatureToggleEvents;
use Fintem\FeatureToggleBundle\Entity\Feature;
use Fintem\FeatureToggleBundle\Event\FeatureValidateEvent;
use Fintem\FeatureToggleBundle\Exception\FeatureNotFoundException;
use Fintem\FeatureToggleBundle\Model\FeatureModel;

/**
 * Class FeatureStatusChecker.
 */
class FeatureStatusChecker
{
    /**
     * @var null|EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var null|LoggerInterface
     */
    private $logger;
    /**
     * @var FeatureModel
     */
    private $model;

    /**
     * FeatureStatusChecker constructor.
     *
     * @param FeatureModel $model
     * @param LoggerInterface|null $logger
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(FeatureModel $model, LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null)
    {
        $this->model = $model;
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $featureName
     * @param bool   $logNotFound
     *
     * @return null|Feature
     */
    public function getByName(string $featureName, bool $logNotFound = true)
    {
        $feature = $this->model->getByName($featureName);
        if (null === $feature) {
            if ($logNotFound && null !== $this->logger) {
                $ex = FeatureNotFoundException::createByName($featureName);
                $this->logger->critical($ex->getMessage(), ['exception' => $ex]);
            }

            return null;
        }

        return $feature;
    }

    /**
     * @param string|Feature $feature
     *
     * @return bool
     */
    public function isEnabled($feature): bool
    {
        $featureName = $feature instanceof Feature ? $feature->getName() : (string) $feature;
        if (is_string($feature)) {
            $cached = $this->model->getCachedValue($featureName);
            if (true === $cached) {
                return true;
            }
            $feature = $this->getByName($featureName);
        }

        if (!$feature instanceof Feature || !$feature->isEnabled() || !$this->isEnabledRecursively($feature)) {
            $this->model->cache($featureName, false);

            return false;
        }
        if (null !== $this->dispatcher) {
            $eventName = sprintf('%s.%s', FeatureToggleEvents::PRE_VALIDATE, $feature->getName());
            $event = $this->dispatcher->dispatch($eventName, new FeatureValidateEvent($feature));
            if (!$event->isValid()) {
                $this->model->cache($featureName, false);

                return false;
            }
        }

        $this->model->cache($featureName, true);

        return true;
    }

    /**
     * @param Feature $feature
     *
     * @return bool
     */
    private function isEnabledRecursively(Feature $feature): bool
    {
        if (!$feature->hasDependencies()) {
            return true;
        }
        foreach ($feature->getDependencies() as $dependency) {
            if (!$dependency->isEnabled() ||
                ($dependency->hasDependencies() && !$this->isEnabledRecursively($dependency))
            ) {
                return false;
            }
        }

        return true;
    }
}
