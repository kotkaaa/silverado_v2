<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;

/**
 * This is the model class for table "product_review".
 *
 * @property string $uuid
 * @property string $product_uuid
 * @property string $review_uuid
 *
 * @property Product $product
 * @property Review $review
 */
class ProductReview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_uuid', 'review_uuid'], 'required'],
            [['uuid', 'product_uuid', 'review_uuid'], 'string', 'max' => 36],
            [['uuid'], 'unique'],
            [['product_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_uuid' => 'uuid']],
            [['review_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Review::className(), 'targetAttribute' => ['review_uuid' => 'uuid']],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => UuidBehavior::class,
                'version' => Uuid::V4,
                'defaultAttribute' => 'uuid',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uuid' => 'Uuid',
            'product_uuid' => 'Product Uuid',
            'review_uuid' => 'Review Uuid',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['uuid' => 'product_uuid']);
    }

    /**
     * Gets query for [[Review]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ReviewQuery
     */
    public function getReview()
    {
        return $this->hasOne(Review::className(), ['uuid' => 'review_uuid']);
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\ProductReviewQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ProductReviewQuery(get_called_class());
    }
}
