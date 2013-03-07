<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Module
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Module
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class ModuleTest extends TestCase
{
    /** @var Module */
    private $module;

    protected function setUp()
    {
        parent::setUp();
        $this->module = new Module();
    }

    /** @test */
    public function implementsAutoloaderProviderInterface()
    {
        $this->assertInstanceOf('Zend\ModuleManager\Feature\AutoloaderProviderInterface', $this->module);
    }

    /**
     * @test
     * @depends implementsAutoloaderProviderInterface
     */
    public function providesAutoloaderConfigAsArray()
    {
        $this->assertInternalType('array', $this->module->getAutoloaderConfig());
    }
}
