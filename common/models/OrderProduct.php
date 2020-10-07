<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\queries\OrderProductQuery;
use common\queries\OrderQuery;
use common\queries\ProductQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

/**
 * This is the model class for table "order_product".
 *
 * @property string $uuid
 * @property int $order_id
 * @property string|null $product_uuid
 * @property string|null $title
 * @property string|null $sku
 * @property float|null $price
 * @property int|null $quantity
 * @property string|null $options
 *
 * @property Order $order
 * @property Product $productUu
 */
class OrderProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'quantity'], 'default', 'value' => null],
            [['order_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['options'], 'safe'],
            [['uuid', 'product_uuid'], 'string', 'max' => 36],
            [['title'], 'string', 'max' => 255],
            [['sku'], 'string', 'max' => 10],
            [['uuid'], 'unique'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_uuid'], 'exist', 'skipOnError' => false, 'targetClass' => Product::className(), 'targetAttribute' => ['product_uuid' => 'uuid']],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'uuid' => [
                'class' => UuidBehavior::class,
                'version' => Uuid::V4,
                'defaultAttribute' => 'uuid',
            ],
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'order'
                ],
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
            'order_id' => 'Order ID',
            'product_uuid' => 'Product Uuid',
            'title' => 'Title',
            'sku' => 'Sku',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'options' => 'Options',
        ];
    }

    /**
     * Gets query for [[Order]].
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * Gets query for [[Product]].
     * @return \yii\db\ActiveQuery|ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['uuid' => 'product_uuid']);
    }

    /**
     * {@inheritdoc}
     * @return OrderProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderProductQuery(get_called_class());
    }
}
