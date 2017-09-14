<?php

namespace Fintem\FeatureToggleBundle\Tests\Unit\Exception;

use Fintem\FeatureToggleBundle\Exception\FeatureNotFoundException;

/**
 * Class FeatureNotFoundExceptionTest.
 */
class FeatureNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function messageOnCreateByName()
    {
        $featureName = 'feature_xyz';
        $exception = FeatureNotFoundException::createByName($featureName);
        $expectedMessage = 'Feature feature_xyz not found.';

        static::assertEquals($expectedMessage, $exception->getMessage());
    }
}
