<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\Service;
use DkplusCrud\Service\Feature as ServiceFeature;
use RuntimeException;
use Zend\EventManager\EventInterface as Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class UnionFilter extends AbstractFeature
{
    /** @var string */
    protected $eventType = self::EVENT_TYPE_MAIN;

    /** @var int */
    protected $priority = 2;

    /** @var Service */
    protected $service;

    /** @var string[] */
    protected $properties;

    /** @var string[] */
    protected $values;

    /** @var string|null */
    protected $source;

    public function __construct(Service $service, array $properties, array $values, $source = null)
    {
        $this->service     = $service;
        $this->properties  = $properties;
        $this->values      = $values;
        $this->source      = $source;
    }

    public function execute(Event $event)
    {
        $values = array();
        if (\strToLower($this->source) == 'query') {
            foreach ($this->properties as $property) {
                $values[$property] = $this->getController()->params()->fromQuery($this->values[$property]);
            }
        } elseif (\strToLower($this->source) == 'post') {
            foreach ($this->properties as $property) {
                $values[$property] = $this->getController()->params()->fromPost($this->values[$property]);
            }
        } elseif (\strToLower($this->source) == 'route') {
            foreach ($this->properties as $property) {
                $values[$property] = $this->getController()->params()->fromRoute($this->values[$property]);
            }
        } else {
            foreach ($this->properties as $property) {
                $values[$property] = $this->values[$property];
            }
        }

        foreach ($this->properties as $property) {
            $this->service->addFeature(new ServiceFeature\UnionFilter('like', $property, $values[$property]));
        }
    }
}
