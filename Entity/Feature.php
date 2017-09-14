<?php

namespace Fintem\FeatureToggleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Feature.
 *
 * @ORM\Table(
 *     indexes={@ORM\Index(name="name", columns={"name"})}
 * )
 * @ORM\Entity
 */
class Feature
{
    use TimestampableEntity;

    /**
     * @var Feature[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Fintem\FeatureToggleBundle\Entity\Feature")
     * @ORM\JoinTable(
     *     name="feature_dependencies",
     *     joinColumns={@ORM\JoinColumn(name="feature_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="dependency_id", referencedColumnName="id")}
     * )
     */
    private $dependencies;
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(type="bigint", options={"unsigned": true})
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Feature constructor.
     */
    public function __construct()
    {
        $this->dependencies = new ArrayCollection();
    }

    /**
     * @param Feature $feature
     *
     * @return $this
     */
    public function addDependency(Feature $feature)
    {
        if (!$this->dependencies->contains($feature)) {
            $this->dependencies->add($feature);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function disable()
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function enable()
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * @return ArrayCollection|Feature[]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return (bool) $this->dependencies->count();
    }

    /**
     * @internal
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
