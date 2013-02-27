<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
use DkplusCrud\Service\Service;
use DkplusCrud\Service\Feature\Filter as ServiceFilter;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class MultipleInputFilter extends AbstractFeature
{
    /** @var string */
    const SOURCE_ROUTE = 'route';

    /** @var string */
    const SOURCE_QUERY = 'query';

    /** @var string */
    const SOURCE_POST = 'post';

    /** @var string */
    const FILTER_LIKE = 'like';

    /** @var string */
    const FILTER_CONTAINING = '%like%';

    /** @var string */
    const FILTER_STARTING_WITH = 'like%';

    /** @var string */
    const FILTER_ENDING_WITH = '%like';

    /** @var string */
    const FILTER_EQUALS = 'equals';

    /** @var string */
    const FILTER_GREATER_THAN = 'greaterThan';

    /** @var string */
    const FILTER_GREATER_THAN_EQUALS = 'greaterThanEquals';

    /** @var string */
    const FILTER_LESS_THAN = 'lessThan';

    /** @var string */
    const FILTER_LESS_THAN_EQUALS = 'lessThanEquals';

    /** @var string */
    protected $eventType = self::EVENT_TYPE_PRE;

    /** @var int */
    protected $priority = 2;

    /** @var Service */
    protected $service;

    /** @var array */
    protected $propertyInputMap;

    /** @var string|null */
    protected $source;

    /** @var string */
    protected $comparator = 'like';

    /** @var ServiceFilter */
    private $filter;

    public function __construct(Service $service, array $propertyInputMap)
    {
        $this->service          = $service;
        $this->propertyInputMap = $propertyInputMap;
    }

    /** @return ServiceFilter */
    public function getFilter()
    {
        if ($this->filter === null) {
            $this->setFilter(new ServiceFilter());
        }
        return $this->filter;
    }

    public function setFilter(ServiceFilter $filter)
    {
        $this->filter = $filter;
    }

    public function setComparator($comparator)
    {
        $this->comparator = $comparator;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function refineResults()
    {
        $this->getFilter()->refineResults();
    }

    public function enlargeResults()
    {
        $this->getFilter()->enlargeResults();
    }

    public function execute(Event $event)
    {
        foreach ($this->propertyInputMap as $property => $input) {
            $this->setUpFilter($property, $input, $event);
        }

        $this->service->addFeature($this->getFilter());
    }

    protected function setUpFilter($property, $input, Event $event)
    {
        switch ($this->comparator) {
            case self::FILTER_GREATER_THAN_EQUALS:
                $this->getFilter()->greaterThanEquals($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_GREATER_THAN:
                $this->getFilter()->greaterThan($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_LESS_THAN_EQUALS:
                $this->getFilter()->lessThanEquals($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_LESS_THAN:
                $this->getFilter()->lessThan($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_EQUALS:
                $this->getFilter()->equals($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_LIKE:
                $this->getFilter()->like($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_ENDING_WITH:
                $this->getFilter()->like($property, '%' . $this->getInputValue($input, $event));
                break;
            case self::FILTER_STARTING_WITH:
                $this->getFilter()->like($property, $this->getInputValue($input, $event) . '%');
                break;
            default:
                $this->getFilter()->like($property, '%' . $this->getInputValue($input, $event) . '%');
                break;
        }
    }

    protected function getInputValue($input, Event $event)
    {
        if ($this->source == self::SOURCE_POST) {
            return $event->getController()->params()->fromPost($input);
        } elseif ($this->source == self::SOURCE_QUERY) {
            return $event->getController()->params()->fromQuery($input);
        } elseif ($this->source == self::SOURCE_ROUTE) {
            return $event->getController()->params()->fromRoute($input);
        }

        return $input;
    }
}
