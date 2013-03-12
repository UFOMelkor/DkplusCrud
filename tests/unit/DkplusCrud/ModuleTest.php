<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
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
