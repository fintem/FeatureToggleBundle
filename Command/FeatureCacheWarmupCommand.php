<?php

namespace Fintem\FeatureToggleBundle\Command;

use Fintem\FeatureToggleBundle\Service\FeatureStatusChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fintem\FeatureToggleBundle\Model\FeatureModel;

/**
 * Class FeatureCacheWarmupCommand.
 */
class FeatureCacheWarmupCommand extends Command
{
    /**
     * @var FeatureModel
     */
    private $model;
    /**
     * @var FeatureStatusChecker
     */
    private $statusChecker;

    /**
     * FeatureCacheWarmupCommand constructor.
     *
     * @param FeatureModel         $model
     * @param FeatureStatusChecker $featureStatusChecker
     */
    public function __construct(FeatureModel $model, FeatureStatusChecker $featureStatusChecker)
    {
        $this->model = $model;
        $this->statusChecker = $featureStatusChecker;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('feature_toggle:cache:warmup');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->model->hasCache() || !$this->model->hasCacheKeyGenerator()) {
            throw new \Exception('FeatureToggleBundle: cache provider is not configured.');
        }
        $features = $this->model->getAll();
        foreach ($features as $feature) {
            $this->model->clearCache($feature, false);
        }
        foreach ($features as $feature) {
            $this->statusChecker->isEnabled($feature);
        }
    }
}
