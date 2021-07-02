<?php

namespace common\queries;

use common\models\Product;
use common\models\Review;

/**
 * This is the ActiveQuery class for [[\common\models\ProductReview]].
 *
 * @see \common\models\ProductReview
 */
class ProductReviewQuery extends \yii\db\ActiveQuery
{
    /**
     * @param Product $product
     * @return ProductReviewQuery
     */
    public function product(Product $product): ProductReviewQuery
    {
        return $this->andWhere([
            'product_uuid' => $product->uuid
        ]);
    }

    /**
     * @param Review $review
     * @return ProductReviewQuery
     */
    public function review(Review $review): ProductReviewQuery
    {
        return $this->andWhere([
            'review_uuid' => $review->uuid
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProductReview[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\ProductReview|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
