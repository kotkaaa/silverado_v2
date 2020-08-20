<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;

/**
 * This is the model class for table "product_option".
 *
 * @property string $uuid
 * @property string $product_uuid
 * @property string $value_uuid
 *
 * @property OptionValue $value
 * @property Product $product
 */
class ProductOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_uuid', 'value_uuid'], 'required'],
            [['uuid', 'product_uuid', 'value_uuid'], 'string', 'max' => 36],
            [['uuid'], 'unique'],
            [['product_uuid', 'value_uuid'], 'unique', 'targetAttribute' => ['product_uuid', 'value_uuid']],
            [['value_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => OptionValue::className(), 'targetAttribute' => ['value_uuid' => 'uuid']],
            [['product_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_uuid' => 'uuid']],
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
            'value_uuid' => 'Value Uuid',
        ];
    }

    /**
     * Gets query for [[OptionValue]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\OptionValueQuery
     */
    public function getValue()
    {
        return $this->hasOne(OptionValue::className(), ['uuid' => 'value_uuid']);
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
     * {@inheritdoc}
     * @return \common\queries\ProductOptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ProductOptionQuery(get_called_class());
    }
}
