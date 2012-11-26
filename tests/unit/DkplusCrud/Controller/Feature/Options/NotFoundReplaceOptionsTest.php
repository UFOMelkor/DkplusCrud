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
     * @group Component/Listener
     * @group unit
     */
    public function isAnOptionsInstance()
    {
        $this->assertInstanceOf('Zend\Stdlib\AbstractOptions', new NotFoundReplaceOptions());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesContentReplaceController()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceController('My\Controller');

        $this->assertSame('My\Controller', $options->getContentReplaceController());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesApplicationControllerAsInitialContentReplaceController()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame('Application\Controller\Index', $options->getContentReplaceController());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesContentReplaceAction()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceAction('paginate');

        $this->assertSame('paginate', $options->getContentReplaceAction());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesIndexAsInitialContentReplaceAction()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame('index', $options->getContentReplaceAction());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesContentReplaceRoute()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceRoute('user/paginate');

        $this->assertSame('user/paginate', $options->getContentReplaceRoute());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesHomeAsInitialContentReplaceRoute()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame('home', $options->getContentReplaceRoute());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesContentReplaceRouteParams()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceRouteParams(array('foo' => 'bar'));

        $this->assertSame(array('foo' => 'bar'), $options->getContentReplaceRouteParams());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesAnEmptyArrayAsInitialContentReplaceRouteParams()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame(array(), $options->getContentReplaceRouteParams());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesAnErrorMessage()
    {
        $options = new NotFoundReplaceOptions();
        $options->setErrorMessage(':-(');

        $this->assertSame(':-(', $options->getErrorMessage());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function providesInitallyNoErrorMessage()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame(null, $options->getErrorMessage());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function canDetectAnExistingErrorMessage()
    {
        $options = new NotFoundReplaceOptions();
        $options->setErrorMessage(':-(');

        $this->assertTrue($options->hasErrorMessage());
    }

    /**
     * @test
     * @group Component/Listener
     * @group unit
     */
    public function canDetectNotExistingErrorMessage()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertFalse($options->hasErrorMessage());
    }
}
