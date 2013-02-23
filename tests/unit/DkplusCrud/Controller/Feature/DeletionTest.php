<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 * @covers     DkplusCrud\Controller\Feature\Deletion
 */
class DeletionTest extends TestCase
{
    /** @var \Zend\Mvc\Controller\Plugin\Redirect|\PHPUnit_Framework_MockObject_MockObject */
    protected $redirect;

    /** @var Zend\Mvc\Controller\Plugin\FlashMessenger|\PHPUnit_Framework_MockObject_MockObject */
    protected $flashMessenger;

    /** @var \DkplusCrud\Controller\Controller|\PHPUnit_Framework_MockObject_MockObject */
    protected $controller;

    /** @var \DkplusCrud\Service\ServiceInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $service;

    /** @var \DkplusCrud\Controller\Event|\PHPUnit_Framework_MockObject_MockObject */
    protected $event;

    /** @var \DkplusCrud\Controller\Feature\Options\SuccessOptions|\PHPUnit_Framework_MockObject_MockObject */
    protected $options;

    /** @var FormSubmission */
    protected $feature;

    protected function setUp()
    {
        parent::setUp();

        $this->flashMessenger = $this->getMock('Zend\Mvc\Controller\Plugin\FlashMessenger');
        $this->redirect       = $this->getMock('Zend\Mvc\Controller\Plugin\Redirect');
        $this->controller     = $this->getMock('DkplusCrud\Controller\Controller', array('flashMessenger', 'redirect'));
        $this->controller->expects($this->any())
                         ->method('flashMessenger')
                         ->will($this->returnValue($this->flashMessenger));
        $this->controller->expects($this->any())
                         ->method('redirect')
                         ->will($this->returnValue($this->redirect));

        $this->options = $this->getMock('DkplusCrud\Controller\Feature\Options\SuccessOptions');
        $this->service = $this->getMockForAbstractClass('DkplusCrud\Service\ServiceInterface');
        $this->feature = new Deletion($this->service, $this->options);

        $this->event = $this->getMock('DkplusCrud\Controller\Event', array(), array($this->controller));
        $this->event->expects($this->any())
                    ->method('getController')
                    ->will($this->returnValue($this->controller));
    }

    /** @test */
    public function isAFeature()
    {
        $this->assertInstanceOf(
            'DkplusCrud\Controller\Feature\FeatureInterface',
            $this->feature
        );
    }

    /** @test */
    public function deletesTheEntity()
    {
        $entity = $this->getMock('stdClass');

        $this->service->expects($this->any())
                      ->method('delete')
                      ->with($entity);

        $this->event->expects($this->any())
                    ->method('getEntity')
                    ->will($this->returnValue($entity));

        $this->options->expects($this->any())
                      ->method('getComputatedRedirectRouteParams')
                      ->will($this->returnValue(array()));
        $this->redirect->expects($this->any())
                       ->method('toRoute')
                       ->will($this->returnValue($this->getMock('Zend\Http\Response')));

        $this->feature->execute($this->event);
    }

    /** @test */
    public function getsTheSuccessMessageForTheDeletedEntity()
    {
        $entity = $this->getMock('stdClass');

        $this->event->expects($this->any())
                    ->method('getEntity')
                    ->will($this->returnValue($entity));

        $this->options->expects($this->once())
                      ->method('getComputatedMessage')
                      ->with($entity);
        $this->options->expects($this->any())
                      ->method('getComputatedRedirectRouteParams')
                      ->will($this->returnValue(array()));
        $this->redirect->expects($this->any())
                       ->method('toRoute')
                       ->will($this->returnValue($this->getMock('Zend\Http\Response')));

        $this->feature->execute($this->event);
    }

    /** @test */
    public function redirectsToRouteAfterDeletion()
    {
        $entity     = $this->getMock('stdClass');
        $route      = 'my-route';
        $parameters = array('my' => 'param');

        $this->event->expects($this->any())
                    ->method('getEntity')
                    ->will($this->returnValue($entity));

        $this->options->expects($this->any())
                      ->method('getRedirectRoute')
                      ->will($this->returnValue($route));
        $this->options->expects($this->any())
                      ->method('getComputatedRedirectRouteParams')
                      ->with($entity)
                      ->will($this->returnValue($parameters));


        $response = $this->getMock('Zend\Http\Response');
        $this->redirect->expects($this->once())
                       ->method('toRoute')
                       ->with($route, $parameters)
                       ->will($this->returnValue($response));

        $this->event->expects($this->once())
                    ->method('setResponse')
                    ->with($response);

        $this->feature->execute($this->event);
    }

    /** @test */
    public function addsSuccessMessageAfterDeletion()
    {
        $namespace = 'success';
        $message   = 'deletion successful';

        $this->options->expects($this->any())
                      ->method('getComputatedMessage')
                      ->will($this->returnValue($message));
        $this->options->expects($this->any())
                      ->method('getMessageNamespace')
                      ->will($this->returnValue($namespace));
        $this->options->expects($this->any())
                      ->method('getComputatedRedirectRouteParams')
                      ->will($this->returnValue(array()));

        $this->flashMessenger->expects($this->at(0))
                             ->method('setNamespace')
                             ->with($namespace);
        $this->flashMessenger->expects($this->at(1))
                             ->method('addMessage')
                             ->with($message);
        
        $this->redirect->expects($this->any())
                       ->method('toRoute')
                       ->will($this->returnValue($this->getMock('Zend\Http\Response')));

        $this->feature->execute($this->event);
    }
}
