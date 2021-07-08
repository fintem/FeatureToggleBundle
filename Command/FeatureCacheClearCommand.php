<?php

namespace Fintem\FeatureToggleBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fintem\FeatureToggleBundle\Model\FeatureModel;

/**
 * Class FeatureCacheClearCommand.
 */
class FeatureCacheClearCommand extends Command
{
    /**
     * @var FeatureModel
     */
    private $model;

    /**
     * FeatureCreateCommand constructor.
     *
     * @param FeatureModel $model
     */
    public function __construct(FeatureModel $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('feature_toggle:cache:clear');
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
    }
}
