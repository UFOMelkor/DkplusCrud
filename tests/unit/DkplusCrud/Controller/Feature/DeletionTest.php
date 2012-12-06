<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Controller;
use DkplusControllerDsl\Test\TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 * @covers     DkplusCrud\Controller\Feature\Deletion
 */
class DeletionTest extends TestCase
{
    /** @var Controller */
    protected $controller;

    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \Zend\EventManager\MvcEvent|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \DkplusCrud\Controller\Feature\Options\SuccessOptions|\PHPUnit_Framework_MockObject_MockObject */
    protected $options;

    /** @var FormSubmission */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();
        $this->event      = $this->getMockForAbstractClass('Zend\EventManager\EventInterface');
        $this->controller = new Controller();
        $this->options    = $this->getMockIgnoringConstructor('DkplusCrud\Controller\Feature\Options\SuccessOptions');
        $this->service    = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');
        $this->feature    = new Deletion($this->service, $this->options);
        $this->feature->setController($this->controller);

        $this->setUpController($this->controller);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isAFeature()
    {
        $this->assertInstanceOf(
            'DkplusCrud\Controller\Feature\FeatureInterface',
            $this->feature
        );
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function deletesTheEntity()
    {
        $entity = $this->getMock('stdClass');

        $this->service->expects($this->any())
                      ->method('delete')
                      ->with($entity);

        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('entity')
                    ->will($this->returnValue($entity));

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function getsTheSuccessMessageForTheDeletedEntity()
    {
        $entity = $this->getMock('stdClass');

        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('entity')
                    ->will($this->returnValue($entity));

        $this->options->expects($this->once())
                      ->method('getComputatedMessage')
                      ->with($entity);

        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function redirectsToRouteAfterDeletion()
    {
        $entity     = $this->getMock('stdClass');
        $route      = 'my-route';
        $parameters = array('my' => 'param');

        $this->event->expects($this->any())
                    ->method('getParam')
                    ->with('entity')
                    ->will($this->returnValue($entity));

        $this->options->expects($this->any())
                      ->method('getRedirectRoute')
                      ->will($this->returnValue($route));
        $this->options->expects($this->any())
                      ->method('getComputatedRedirectRouteParams')
                      ->with($entity)
                      ->will($this->returnValue($parameters));


        $this->expectsDsl()->toRedirectToRoute($route, $parameters);
        $this->feature->execute($this->event);
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function addsSuccessMessageAfterDeletion()
    {
        $message = 'deletion successful';

        $this->options->expects($this->any())
                      ->method('getcomputatedMessage')
                      ->will($this->returnValue($message));

        $this->expectsDsl()->toAddFlashMessage($message, 'success');
        $this->feature->execute($this->event);
    }
}
