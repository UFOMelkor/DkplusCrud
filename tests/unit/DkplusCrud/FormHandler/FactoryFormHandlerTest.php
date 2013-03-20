<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\FormHandler;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.2.0
 * @covers DkplusCrud\FormHandler\FactoryFormHandler
 */
class FactoryFormHandlerTest extends TestCase
{
    /** @var \Zend\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $form;

    /** @var \DkplusBase\Stdlib\Hydrator\HydrationFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $hydrationFactory;

    /** @var FactoryFormHandler */
    private $formHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->form             = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $this->hydrationFactory = $this->getMockForAbstractClass(
            'DkplusBase\Stdlib\Hydrator\HydrationFactoryInterface'
        );
        $this->formHandler     = new FactoryFormHandler($this->form, $this->hydrationFactory);
    }

    /** @test */
    public function isAFormStrategy()
    {
        $this->assertInstanceOf('DkplusCrud\FormHandler\FormHandlerInterface', $this->formHandler);
    }

    /** @test */
    public function returnsTheOvergivenFormAsCreationForm()
    {
        $this->assertSame($this->form, $this->formHandler->getCreationForm());
    }

    /** @test */
    public function returnsTheOvergivenFormAsUpdateForm()
    {
        $entity = $this->getMock('stdClass');
        $this->assertSame($this->form, $this->formHandler->getUpdateForm($entity));
    }

    /** @test */
    public function putsTheDataOfTheModelIntoTheUpdateForm()
    {
        $entity = $this->getMock('stdClass');
        $data = array('foo' => 'bar');

        $this->hydrationFactory->expects($this->any())
                               ->method('extract')
                               ->with($entity)
                               ->will($this->returnValue($data));

        $this->form->expects($this->once())
                   ->method('populateValues')
                   ->with($data);

        $this->formHandler->getUpdateForm($entity);
    }

    /** @test */
    public function createsNewEntitiesUsingTheHydrationFactory()
    {
        $entity = $this->getMock('stdClass');
        $data = array('foo' => 'bar');

        $this->hydrationFactory->expects($this->any())
                               ->method('create')
                               ->with($data)
                               ->will($this->returnValue($entity));

        $this->assertSame($entity, $this->formHandler->createEntity($data));
    }

    /** @test */
    public function updatesEntitiesUsingTheHydrationFactory()
    {
        $data = array('foo', 'bar', 'baz');
        $entity = $this->getMock('stdClass');

        $this->hydrationFactory->expects($this->once())
                               ->method('hydrate')
                               ->with($data, $entity);

        $this->formHandler->updateEntity($data, $entity);
    }

    /** @test */
    public function returnsTheUpdatedEntity()
    {
        $entity = $this->getMock('stdClass');

        $this->assertSame($entity, $this->formHandler->updateEntity(array(), $entity));
    }
}
