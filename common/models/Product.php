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
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "product".
 *
 * @property string $uuid
 * @property string $sku
 * @property string|null $category_uuid
 * @property string $title
 * @property string|null $description
 * @property string|null $short
 * @property float|null $price
 * @property int|null $discount
 * @property int|null $viewed
 * @property int|null $purchased
 * @property int|null $rating
 * @property int|null $position
 * @property bool $active
 * @property string $alias
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $meta_robots
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{

    use OptionalActiveRecordTrait;

    /** @var bool */
    public const ACTIVE_STATE_TRUE = true;
    public const ACTIVE_STATE_FALSE = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku', 'title'], 'required'],
            [['description', 'short', 'meta_description', 'meta_keywords'], 'string'],
            [['price'], 'number'],
            [['price'], 'default', 'value' => 0.00],
            [['discount', 'viewed', 'purchased', 'rating', 'position'], 'integer'],
            [['discount', 'viewed', 'purchased', 'rating'], 'default', 'value' => null],
            [['position'], 'default', 'value' => 1],
            [['created_at', 'updated_at'], 'safe'],
            [['uuid', 'category_uuid'], 'string', 'max' => 36],
            [['sku'], 'string', 'max' => 32],
            [['active'], 'boolean'],
            [['active'], 'default', 'value' => true],
            [['active'], 'in', 'range' => self::activeStates(true)],
            [['title', 'alias', 'meta_title'], 'string', 'max' => 255],
            [['meta_robots'], 'string', 'max' => 32],
            [['sku', 'uuid'], 'unique'],
            [['category_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_uuid' => 'uuid']],
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
                'attribute' => 'sku',
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
                    'category',
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
            'sku' => 'Sku',
            'category_uuid' => 'Category Uuid',
            'title' => 'Title',
            'description' => 'Description',
            'short' => 'Short',
            'price' => 'Price',
            'discount' => 'Discount',
            'viewed' => 'Viewed',
            'purchased' => 'Purchased',
            'rating' => 'Rating',
            'position' => 'Position',
            'active' => 'Active',
            'alias' => 'Alias',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'meta_robots' => 'Meta Robots',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\CategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['uuid' => 'category_uuid']);
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ProductQuery(get_called_class());
    }

    /**
     * @param bool $onlyKeys
     * @return array
     */
    public static function activeStates($onlyKeys = false): array
    {
        $states = [
            self::ACTIVE_STATE_TRUE => 'Да',
            self::ACTIVE_STATE_FALSE => 'Нет'
        ];

        if ($onlyKeys) {
            return array_keys($states);
        }

        return $states;
    }
}
