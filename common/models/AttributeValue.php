<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\classes\Optional\OptionalActiveRecordTrait;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "attribute_value".
 *
 * @property string $uuid
 * @property string $attribute_uuid
 * @property string $title
 * @property string $alias
 * @property int|null $position
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Attribute $attributeModel
 * @property ProductAttribute[] $productAttributes
 * @property Product[] $product
 */
class AttributeValue extends \yii\db\ActiveRecord
{
    use OptionalActiveRecordTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attribute_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_uuid', 'title'], 'required'],
            [['position'], 'default', 'value' => 1],
            [['position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uuid', 'attribute_uuid'], 'string', 'max' => 36],
            [['title', 'alias'], 'string', 'max' => 255],
            [['uuid', 'alias'], 'unique'],
            [['attribute_uuid', 'title'], 'unique', 'targetAttribute' => ['attribute_uuid', 'title']],
            [['attribute_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Attribute::className(), 'targetAttribute' => ['attribute_uuid' => 'uuid']],
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
            'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'alias',
                'ensureUnique' => true,
                'immutable' => true
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('now()'),
            ],
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'attributeModel',
                    'productAttributes'
                ],
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
            'attribute_uuid' => 'Attribute Uuid',
            'title' => 'Title',
            'alias' => 'Alias',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AttributeUu]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\AttributeQuery
     */
    public function getAttributeModel()
    {
        return $this->hasOne(Attribute::className(), ['uuid' => 'attribute_uuid']);
    }

    /**
     * Gets query for [[ProductAttributes]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ProductAttributeQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['value_uuid' => 'uuid']);
    }

    /**
     * Gets query for [[ProductUus]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ProductQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['uuid' => 'product_uuid'])->via('productAttributes');
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\AttributeValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\AttributeValueQuery(get_called_class());
    }
}
