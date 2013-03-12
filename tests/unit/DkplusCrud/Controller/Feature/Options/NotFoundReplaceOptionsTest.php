<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature\Options;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class NotFoundReplaceOptionsTest extends TestCase
{
    /** @test */
    public function isAnOptionsInstance()
    {
        $this->assertInstanceOf('Zend\Stdlib\AbstractOptions', new NotFoundReplaceOptions());
    }

    /** @test */
    public function providesContentReplaceController()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceController('My\Controller');

        $this->assertSame('My\Controller', $options->getContentReplaceController());
    }

    /** @test */
    public function providesApplicationControllerAsInitialContentReplaceController()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame('Application\Controller\Index', $options->getContentReplaceController());
    }

    /** @test */
    public function providesContentReplaceRoute()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceRoute('user/paginate');

        $this->assertSame('user/paginate', $options->getContentReplaceRoute());
    }

    /** @test */
    public function providesNullAsInitialContentReplaceRoute()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertNull($options->getContentReplaceRoute());
    }

    /** @test */
    public function providesContentReplaceRouteParams()
    {
        $options = new NotFoundReplaceOptions();
        $options->setContentReplaceRouteParams(array('foo' => 'bar'));

        $this->assertSame(array('foo' => 'bar'), $options->getContentReplaceRouteParams());
    }

    /** @test */
    public function providesAnActionAsInitialContentReplaceRouteParams()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame(array('action' => 'index'), $options->getContentReplaceRouteParams());
    }

    /** @test */
    public function providesAnErrorMessage()
    {
        $options = new NotFoundReplaceOptions();
        $options->setErrorMessage(':-(');

        $this->assertSame(':-(', $options->getErrorMessage());
    }

    /** @test */
    public function providesInitallyNoErrorMessage()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertSame(null, $options->getErrorMessage());
    }

    /** @test */
    public function canDetectAnExistingErrorMessage()
    {
        $options = new NotFoundReplaceOptions();
        $options->setErrorMessage(':-(');

        $this->assertTrue($options->hasErrorMessage());
    }

    /** @test */
    public function canDetectNotExistingErrorMessage()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertFalse($options->hasErrorMessage());
    }

    /** @test */
    public function providesAMessageNamespace()
    {
        $options = new NotFoundReplaceOptions();
        $options->setMessageNamespace('error');

        $this->assertEquals('error', $options->getMessageNamespace());
    }

    /** @test */
    public function hasADefaultMessageNamespace()
    {
        $options = new NotFoundReplaceOptions();

        $this->assertEquals('404-not-found', $options->getMessageNamespace());
    }
}
