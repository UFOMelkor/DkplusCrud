<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 * @covers DkplusCrud\Controller\ConfigurationError
 */
class ConfigurationErrorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function extendsTheRuntimeException()
    {
        $this->assertInstanceOf('RuntimeException', new ConfigurationError('foo', 'bar'));
    }

    /** @test */
    public function containsTheEventNameInTheMessage()
    {
        $exception = new ConfigurationError('event-name', 'missed-param');
        $this->assertContains('event-name', $exception->getMessage());
    }

    /** @test */
    public function containsTheMissedParamInTheMessage()
    {
        $exception = new ConfigurationError('event-name', 'missed-param');
        $this->assertContains('missed-param', $exception->getMessage());
    }
}
