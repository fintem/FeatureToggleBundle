<?php

namespace Fintem\FeatureToggleBundle\Model;

use Doctrine\ORM\EntityManagerInterface;
use Fintem\FeatureToggleBundle\Constant\FeatureToggleEvents;
use Fintem\FeatureToggleBundle\Entity\Feature;
use Fintem\FeatureToggleBundle\Event\FeatureAwareEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FeatureModel.
 */
class FeatureModel
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * FeatureModel constructor.
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher = null)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Feature $feature
     *
     * @return $this
     */
    public function remove(Feature $feature)
    {
        $this->em->remove($feature);
        $this->em->flush();

        return $this;
    }

    /**
     * @param Feature $feature
     * @param Feature $dependency
     * @param bool    $flush
     *
     * @return $this
     */
    public function addDependency(Feature $feature, Feature $dependency, bool $flush = true)
    {
        $feature->addDependency($dependency);
        if ($flush) {
            $this->em->flush();
        }

        return $this;
    }

    /**
     * @param string $name
     * @param bool   $enable
     * @param bool   $flush
     *
     * @return Feature
     */
    public function create(string $name, bool $enable = false, bool $flush = true): Feature
    {
        $feature = (new Feature())->setName($name);
        if ($enable) {
            $feature->enable();
        } else {
            $feature->disable();
        }
        $this->em->persist($feature);
        if ($flush) {
            $this->em->flush();
        }

        return $feature;
    }

    /**
     * @param Feature $feature
     * @param bool    $flush
     *
     * @return $this
     */
    public function disable(Feature $feature, bool $flush = true)
    {
        $feature->disable();
        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch(FeatureToggleEvents::PRE_DISABLE, new FeatureAwareEvent($feature));
        }
        if ($flush) {
            $this->em->flush();
        }

        return $this;
    }

    /**
     * @param Feature $feature
     * @param bool    $flush
     *
     * @return $this
     */
    public function enable(Feature $feature, bool $flush = true)
    {
        $feature->enable();
        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch(FeatureToggleEvents::PRE_ENABLE, new FeatureAwareEvent($feature));
        }
        if ($flush) {
            $this->em->flush();
        }

        return $this;
    }

    /**
     * @return Feature[]
     */
    public function getAll(): array
    {
        $repo = $this->em->getRepository(Feature::class);

        return $repo->findAll();
    }

    /**
     * @param string $featureName
     *
     * @return null|Feature
     */
    public function getByName(string $featureName)
    {
        $repo = $this->em->getRepository(Feature::class);

        return $repo->findOneBy(['name' => $featureName]);
    }
}
