<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller
 * @author     Oskar Bley <oskar@programming-php.net>
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
