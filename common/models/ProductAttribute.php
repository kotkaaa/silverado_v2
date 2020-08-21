<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\queries\AttributeQuery;
use common\queries\AttributeValueQuery;
use common\queries\ProductQuery;

/**
 * This is the model class for table "product_attribute".
 *
 * @property string $uuid
 * @property string $product_uuid
 * @property string $value_uuid
 *
 * @property AttributeValue $value
 * @property Product $product
 * @property Attribute $attributeModel
 */
class ProductAttribute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_uuid', 'value_uuid'], 'required'],
            [['uuid', 'product_uuid', 'value_uuid'], 'string', 'max' => 36],
            [['product_uuid', 'value_uuid'], 'unique', 'targetAttribute' => ['product_uuid', 'value_uuid']],
            [['uuid'], 'unique'],
            [['value_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => AttributeValue::className(), 'targetAttribute' => ['value_uuid' => 'uuid']],
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
     * Gets query for [[Value]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\AttributeValueQuery
     */
    public function getValue(): AttributeValueQuery
    {
        return $this->hasOne(AttributeValue::className(), ['uuid' => 'value_uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery|\common\queries\AttributeQuery
     */
    public function getAttributeModel(): AttributeQuery
    {
        return $this->hasOne(Attribute::className(), ['uuid' => 'attribute_uuid'])->via('value');
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ProductQuery
     */
    public function getProduct(): ProductQuery
    {
        return $this->hasOne(Product::className(), ['uuid' => 'product_uuid']);
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\ProductAttributeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ProductAttributeQuery(get_called_class());
    }
}
