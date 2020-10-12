<?php


namespace common\strategies;

use common\builders\FilterQueryBuilder;
use common\builders\FilterUrlBuilder;
use common\models\Category;
use common\models\Filter;
use common\models\FilterValue;

/**
 * Class FilterStrategy
 * @package common\strategies
 */
class FilterStrategy extends \yii\base\BaseObject
{
    /** @var Filter */
    public $filter;

    /** @var Category */
    public $category;

    /** @var array */
    public $selectedFilters = [];

    /** @var FilterQueryBuilder */
    public $queryBuilder;

    /** @var FilterUrlBuilder */
    public $urlBuilder;

    /** @var int */
    protected $count = 0;

    /** @var FilterValue[] */
    protected $values = [];

    /**
     * @return FilterValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}