<?php

namespace common\models;

use common\queries\OrderInfoQuery;
use common\queries\OrderQuery;

/**
 * This is the model class for table "order_info".
 *
 * @property string $uuid
 * @property int $order_id
 * @property string|null $payment_type
 * @property string|null $delivery_type
 * @property string|null $user_name
 * @property string|null $user_phone
 * @property string|null $user_email
 * @property string|null $comment
 * @property string|null $location
 * @property string|null $address
 * @property string|null $recepient_name
 * @property string|null $recepient_phone
 *
 * @property Order $order
 */
class OrderInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id'], 'default', 'value' => null],
            [['order_id'], 'integer'],
            [['comment'], 'string'],
            [['uuid'], 'string', 'max' => 36],
            [['payment_type', 'delivery_type', 'user_phone', 'recepient_phone'], 'string', 'max' => 32],
            [['user_name', 'location', 'address', 'recepient_name'], 'string', 'max' => 255],
            [['user_email'], 'string', 'max' => 64],
            [['uuid', 'order_id'], 'unique'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'payment_type' => 'Payment Type',
            'delivery_type' => 'Delivery Type',
            'user_name' => 'User Name',
            'user_phone' => 'User Phone',
            'user_email' => 'User Email',
            'comment' => 'Comment',
            'location' => 'Location',
            'address' => 'Address',
            'recepient_name' => 'Recepient Name',
            'recepient_phone' => 'Recepient Phone',
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
     * {@inheritdoc}
     * @return OrderInfoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderInfoQuery(get_called_class());
    }
}
