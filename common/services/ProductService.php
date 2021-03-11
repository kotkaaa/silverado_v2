<?php


namespace common\services;

use common\models\Category;
use common\models\Product;
use common\queries\ProductQuery;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQueryInterface;
use yii\db\Expression;

/**
 * Class ProductService
 * @package common\services
 */
class ProductService
{
    /**
     * @param Category $category
     * @param string $order
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function search(Category $category = null, int $limit): DataProviderInterface
    {
        return new ActiveDataProvider([
            'query' => $this->find()->category($category),
            'pagination' => [
                'pageSize' => $limit
            ],
            'sort' => [
                'defaultOrder' => [
                    'position' => SORT_ASC
                ]
            ]
        ]);
    }

    /**
     * @return ProductQuery
     */
    public function find(): ActiveQueryInterface
    {
        return Product::find()->active();
    }
}