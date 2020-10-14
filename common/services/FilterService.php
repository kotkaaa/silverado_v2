<?php


namespace common\services;

use common\builders\FilterUrlBuilder;
use common\models\Category;
use common\models\Filter;
use yii\base\Model;

/**
 * Class FilterService
 * @package common\services
 */
class FilterService
{
    /**
     * @param Category $category
     * @param array $filters
     * @return \Generator
     */
    public function getCategoryFilters(Model $searchModel)
    {
        foreach (Filter::find()->ordered()->all() as $filter)
        {
            $filter->strategy = \Yii::configure($filter->strategy, [
                'category' => $searchModel->category,
                'selectedFilters' => $searchModel->filters,
                'queryBuilder' => $searchModel,
                'urlBuilder' => \Yii::createObject([
                    'class' => FilterUrlBuilder::class,
                    'category' => $searchModel->category,
                    'attributes' => $searchModel->filters
                ])
            ]);

            $filter->strategy->initValues();

            yield $filter;
        }
    }

    /**
     * @param string|null $query
     * @return array
     */
    public function parseQuery(string $query = null): array
    {
        if (!$query) {
            return [];
        }

        return explode(',', $query);
    }
}