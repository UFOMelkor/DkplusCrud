<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Action;

use DkplusCrud\Controller\Controller;
use DkplusCrud\Controller\Feature\FeatureInterface as Feature;
use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
interface ActionInterface
{
    /** @return string */
    public function getName();

    public function addFeature(Feature $feature);

    public function setController(Controller $controller);

    public function attachTo(EventManager $events);

    /** @return \Zend\View\Model\ModelInterface|Zend\Http\Response|array */
    public function execute();
}
