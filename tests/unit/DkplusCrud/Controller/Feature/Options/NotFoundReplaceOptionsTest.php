<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature\Options;

use DkplusUnitTest\TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class NotFoundReplaceOptionsTest extends TestCase
{
    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isAnOptionsInstance()
    {
        $this->assertInstanceOf('Zend\Stdlib\AbstractOptions', new NotFoundReplaceOptions());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesContentReplaceController()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceController('My\Controller');

        $this->assertSame('My\Controller', $options->getContentReplaceController());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesApplicationControllerAsInitialContentReplaceController()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame('Application\Controller\Index', $options->getContentReplaceController());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesContentReplaceAction()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceAction('paginate');

        $this->assertSame('paginate', $options->getContentReplaceAction());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesIndexAsInitialContentReplaceAction()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame('index', $options->getContentReplaceAction());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesContentReplaceRoute()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceRoute('user/paginate');

        $this->assertSame('user/paginate', $options->getContentReplaceRoute());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesHomeAsInitialContentReplaceRoute()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame('home', $options->getContentReplaceRoute());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesContentReplaceRouteParams()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceRouteParams(array('foo' => 'bar'));

        $this->assertSame(array('foo' => 'bar'), $options->getContentReplaceRouteParams());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesAnEmptyArrayAsInitialContentReplaceRouteParams()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame(array(), $options->getContentReplaceRouteParams());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesAnErrorMessage()
    {
        $options = new NotFoundReplaceOptions();
        $options->setErrorMessage(':-(');

        $this->assertSame(':-(', $options->getErrorMessage());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function providesInitallyNoErrorMessage()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame(null, $options->getErrorMessage());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canDetectAnExistingErrorMessage()
    {
        $options = new NotFoundReplaceOptions();
        $options->setErrorMessage(':-(');

        $this->assertTrue($options->hasErrorMessage());
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function canDetectNotExistingErrorMessage()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertFalse($options->hasErrorMessage());
    }
}
