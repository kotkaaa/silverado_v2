<?php

namespace common\builders;

use common\models\AttributeValue;
use common\models\Category;
use common\models\OptionValue;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;
use yii\data\Pagination;

/**
 * FilterQueryBuilder represents the model behind the search form of `common\models\Product`.
 */
class FilterQueryBuilder extends Model
{
    /** @var Category */
    public $category;

    /** @var array */
    public $filters;

    /** @var array */
    protected $attributeFilters = [];

    /** @var array */
    protected $optionFilters = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->restrictFilters();
        parent::init();
    }

    /**
     * @return void
     */
    public function restrictFilters(): void
    {
        foreach ($this->filters as $alias)
        {
            if (($attributeValue = AttributeValue::find()->andWhere(['alias' => $alias])->cache(300)->one()->orNull()) !== null) {
                $this->attributeFilters[] = $alias;
                continue;
            }

            if (($optionValue = OptionValue::find()->andWhere(['alias' => $alias])->cache(300)->one()->orNull()) !== null) {
                $this->optionFilters[] = $alias;
                continue;
            }
        }
    }

    /**
     * @param $filter
     * @return FilterQueryBuilder
     */
    public function appendFilter($filter): FilterQueryBuilder
    {
        if (($key = array_search($filter, $this->filters)) !== false) {
            unset($this->filters[$key]);
            $this->restrictFilters();
        }

        return $this;
    }

    /**
     * @param $filter
     * @return FilterQueryBuilder
     */
    public function removeFilter($filter): FilterQueryBuilder
    {
        if (!in_array($filter, $this->filters)) {
            $this->filters[] = $filter;
            $this->restrictFilters();
        }

        return $this;
    }

    /**
     * @param $filter
     * @return FilterQueryBuilder
     */
    public function appendOptionFilter($filter): FilterQueryBuilder
    {
        if (($key = array_search($filter, $this->optionFilters)) !== false) {
            unset($this->optionFilters[$key]);
        }

        return $this;
    }

    /**
     * @param $filter
     * @return FilterQueryBuilder
     */
    public function removeOptionFilter($filter): FilterQueryBuilder
    {
        if (!in_array($filter, $this->optionFilters)) {
            $this->optionFilters[] = $filter;
        }

        return $this;
    }

    /**
     * @param $filter
     * @return FilterQueryBuilder
     */
    public function appendAttributeFilter($filter): FilterQueryBuilder
    {
        if (($key = array_search($filter, $this->attributeFilters)) !== false) {
            unset($this->attributeFilters[$key]);
        }

        return $this;
    }

    /**
     * @param $filter
     * @return FilterQueryBuilder
     */
    public function removeAttributeFilter($filter): FilterQueryBuilder
    {
        if (!in_array($filter, $this->attributeFilters)) {
            $this->attributeFilters[] = $filter;
        }

        return $this;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param bool $count
     *
     * @return ActiveDataProvider
     */
    public function search($count = false)
    {
        $query = Product::find()
            ->category($this->category)
            ->cache(300);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'attributes' => [
                    'created_at' => [
                        'asc' => [
                            'created_at' => SORT_ASC
                        ],
                        'desc' => [
                            'created_at' => SORT_DESC
                        ],
                    ],
                    'updated_at' => [
                        'asc' => [
                            'updated_at' => SORT_ASC
                        ],
                        'desc' => [
                            'updated_at' => SORT_DESC
                        ],
                    ],
                    'title' => [
                        'asc' => [
                            'title' => SORT_ASC
                        ],
                        'desc' => [
                            'title' => SORT_DESC
                        ],
                    ],
                    'category_title' => [
                        'asc' => [
                            'category.title' => SORT_ASC
                        ],
                        'desc' => [
                            'category.title' => SORT_DESC
                        ],
                    ],
                    'sku',
                    'price',
                    'active',
                    'position',
                ],
                'defaultOrder' => [
                    'position' => SORT_ASC
                ],
            ]
        ]);

        $query->joinWith('attributeValues attributeValues')
            ->andFilterWhere(['attributeValues.alias' => $this->attributeFilters])
            ->joinWith('optionValues optionValues')
            ->andFilterWhere(['optionValues.alias' => $this->optionFilters]);

        $query->groupBy(['product.uuid']);

        if ($count) {
            return $dataProvider->totalCount;
        }

        $dataProvider->setPagination(new Pagination([
            'totalCount' => $dataProvider->getTotalCount(),
            'pageSize' => 20,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]));

        return $dataProvider;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->search(true);
    }
}
