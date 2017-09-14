<?php

namespace Fintem\FeatureToggleBundle\Twig;

use Fintem\FeatureToggleBundle\Service\FeatureStatusChecker;

/**
 * Class FeatureExtension.
 */
class FeatureExtension extends \Twig_Extension
{
    /**
     * @var FeatureStatusChecker
     */
    private $checker;

    /**
     * FeatureExtension constructor.
     *
     * @param FeatureStatusChecker $checker
     */
    public function __construct(FeatureStatusChecker $checker)
    {
        $this->checker = $checker;
    }

    /**
     * @param string $featureName
     *
     * @return bool
     */
    public function featureStatusFunction(string $featureName)
    {
        return $this->checker->isEnabled($featureName);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('is_feature_enabled', [$this, 'featureStatusFunction']),
        ];
    }
}
