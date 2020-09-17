<?php

namespace common\models;

use common\queries\OrderInfoQuery;
use common\queries\OrderProductQuery;
use common\queries\OrderQuery;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property OrderInfo $orderInfo
 * @property OrderProduct[] $orderProducts
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[OrderInfo]].
     * @return \yii\db\ActiveQuery|OrderInfoQuery
     */
    public function getOrderInfo()
    {
        return $this->hasOne(OrderInfo::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderProducts]].
     * @return \yii\db\ActiveQuery|OrderProductQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }
}
