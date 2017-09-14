<?php

namespace Fintem\FeatureToggleBundle\Tests\Unit\Exception;

use Fintem\FeatureToggleBundle\Exception\LogicException;

/**
 * Class LogicExceptionTest.
 */
class LogicExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function extendsInternalException()
    {
        $exception = new LogicException();
        static::assertInstanceOf(\LogicException::class, $exception);
    }
}
