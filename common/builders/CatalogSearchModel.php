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
class CatalogSearchModel extends Model
{
    /** @var string */
    public const SORT_PRICE_ASC = 'price';
    public const SORT_PRICE_DESC = '-price';
    public const SORT_SKU_ASC = 'sku';
    public const SORT_SKU_DESC = '-sku';
    public const SORT_PURCHASED_ASC = 'purchased';
    public const SORT_PURCHASED_DESC = '-purchased';

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
     * @return CatalogSearchModel
     */
    public function appendFilter($filter): CatalogSearchModel
    {
        if (($key = array_search($filter, $this->filters)) !== false) {
            unset($this->filters[$key]);
            $this->restrictFilters();
        }

        return $this;
    }

    /**
     * @param $filter
     * @return CatalogSearchModel
     */
    public function removeFilter($filter): CatalogSearchModel
    {
        if (!in_array($filter, $this->filters)) {
            $this->filters[] = $filter;
            $this->restrictFilters();
        }

        return $this;
    }

    /**
     * @param $filter
     * @return CatalogSearchModel
     */
    public function appendOptionFilter($filter): CatalogSearchModel
    {
        if (($key = array_search($filter, $this->optionFilters)) !== false) {
            unset($this->optionFilters[$key]);
        }

        return $this;
    }

    /**
     * @param $filter
     * @return CatalogSearchModel
     */
    public function removeOptionFilter($filter): CatalogSearchModel
    {
        if (!in_array($filter, $this->optionFilters)) {
            $this->optionFilters[] = $filter;
        }

        return $this;
    }

    /**
     * @param $filter
     * @return CatalogSearchModel
     */
    public function appendAttributeFilter($filter): CatalogSearchModel
    {
        if (($key = array_search($filter, $this->attributeFilters)) !== false) {
            unset($this->attributeFilters[$key]);
        }

        return $this;
    }

    /**
     * @param $filter
     * @return CatalogSearchModel
     */
    public function removeAttributeFilter($filter): CatalogSearchModel
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
                    'sku',
                    'price',
                    'purchased' => [
                        'asc' => [
                            'purchased' => SORT_ASC,
                            'sku' => SORT_ASC
                        ],
                        'desc' => [
                            'purchased' => SORT_DESC,
                            'sku' => SORT_ASC
                        ]
                    ]
                ],
                'defaultOrder' => [
                    'purchased' => SORT_DESC,
                    'sku' => SORT_ASC
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

    /**
     * @return string[]
     */
    public function getSorting(): array
    {
        return [
            self::SORT_PRICE_ASC => 'Ціна (за збільшенням)',
            self::SORT_PRICE_DESC => 'Ціна (за зменшенням)',
            self::SORT_SKU_ASC => 'Артикул',
            self::SORT_PURCHASED_ASC => 'Популярні'
        ];
    }

    /**
     * @param $key
     * @return string|null
     */
    public function getSortingLabel($key): ?string
    {
        $sorting = $this->getSorting();

        return array_key_exists($key, $sorting) ? $sorting[$key] : null;
    }
}
