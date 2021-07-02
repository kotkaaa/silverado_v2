<?php

namespace common\services;

use common\models\Product;
use common\models\Review;
use common\queries\ReviewQuery;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;

/**
 * Class ReviewService
 * @package common\services
 */
class ReviewService extends \yii\base\Component
{
    /**
     * @param Product $product
     * @return bool
     */
    public function post(Product $product): bool
    {
        if (($model = new Review()) && $model->load(\Yii::$app->request->post()) && $model->save()) {
            $product->link('reviews', $model);
            return true;
        }

        return false;
    }

    /**
     * @param Product $product
     * @return DataProviderInterface
     */
    public function getByProduct(Product $product): DataProviderInterface
    {
        return new ActiveDataProvider([
            'query' => $product->getReviews()->active(),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
        ]);
    }

    /**
     * @param Product $product
     * @return int|null
     */
    public function getAverageRating(Product $product): ?int
    {
        return $product->getReviews()->active()->average('rate');
    }

    /**
     * @return ReviewQuery
     */
    public static function find(): ReviewQuery
    {
        return Review::find()->active()->ordered();
    }
}