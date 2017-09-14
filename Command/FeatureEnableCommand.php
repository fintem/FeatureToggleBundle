<?php

namespace Fintem\FeatureToggleBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Fintem\FeatureToggleBundle\Entity\Feature;
use Fintem\FeatureToggleBundle\Exception\FeatureNotFoundException;
use Fintem\FeatureToggleBundle\Model\FeatureModel;

/**
 * Class FeatureEnableCommand.
 */
class FeatureEnableCommand extends Command
{
    const ARGUMENT_NAME = 'name';

    /**
     * @var FeatureModel
     */
    private $model;

    /**
     * FeatureEnableCommand constructor.
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
        $this
            ->setName('feature_toggle:enable')
            ->addArgument(self::ARGUMENT_NAME, InputArgument::REQUIRED, 'Feature name');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $featureName = $input->getArgument(self::ARGUMENT_NAME);
        $feature = $this->model->getByName($featureName);
        if (!$feature instanceof Feature) {
            throw FeatureNotFoundException::createByName($featureName);
        }

        $this->model->enable($feature);
    }
}
