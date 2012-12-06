<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\Service;
use DkplusCrud\Service\Feature\Filter as ServiceFilter;
use Zend\EventManager\EventInterface as Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class SingleInputFilter extends AbstractFeature
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

    /** @var string[] */
    protected $properties;

    /** @var string */
    protected $input;

    /** @var string|null */
    protected $source;

    /** @var string */
    protected $comparator = 'like';

    /** @var ServiceFilter */
    private $filter;

    public function __construct(Service $service, array $properties, $input)
    {
        $this->service    = $service;
        $this->properties = $properties;
        $this->input      = $input;
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
        if ($this->source == self::SOURCE_POST) {
            $value = $this->getController()->params()->fromPost($this->input);
        } elseif ($this->source == self::SOURCE_QUERY) {
            $value = $this->getController()->params()->fromQuery($this->input);
        } elseif ($this->source == self::SOURCE_ROUTE) {
            $value = $this->getController()->params()->fromRoute($this->input);
        } else {
            $value = $this->input;
        }

        foreach ($this->properties as $property) {
            switch ($this->comparator) {
                case self::FILTER_GREATER_THAN_EQUALS:
                    $this->getFilter()->greaterThanEquals($property, $value);
                    break;
                case self::FILTER_GREATER_THAN:
                    $this->getFilter()->greaterThan($property, $value);
                    break;
                case self::FILTER_LESS_THAN_EQUALS:
                    $this->getFilter()->lessThanEquals($property, $value);
                    break;
                case self::FILTER_LESS_THAN:
                    $this->getFilter()->lessThan($property, $value);
                    break;
                case self::FILTER_EQUALS:
                    $this->getFilter()->equals($property, $value);
                    break;
                case self::FILTER_LIKE:
                    $this->getFilter()->like($property, $value);
                    break;
                case self::FILTER_ENDING_WITH:
                    $this->getFilter()->like($property, '%' . $value);
                    break;
                case self::FILTER_STARTING_WITH:
                    $this->getFilter()->like($property, $value . '%');
                    break;
                case self::FILTER_CONTAINING:
                default:
                    $this->getFilter()->like($property, '%' . $value . '%');
                    break;
            }
        }

        $this->service->addFeature($this->getFilter());
    }
}
