<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\behaviors\FilterBehavior;
use common\strategies\AttributeFilterStrategy;
use common\strategies\FilterStrategyInterface;
use common\strategies\OptionFilterStrategy;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "filter".
 *
 * @property string $uuid
 * @property string|null $attribute_uuid
 * @property string|null $option_uuid
 * @property string $title
 * @property string $alias
 * @property string $strategy_class
 * @property int|null $position
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Attribute $attributeModel
 * @property Option $optionModel
 */
class Filter extends \yii\db\ActiveRecord
{
    /** @var FilterStrategyInterface */
    public $strategy;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'strategy_class'], 'required'],
            [['position'], 'default', 'value' => 1],
            [['position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uuid', 'attribute_uuid', 'option_uuid'], 'string', 'max' => 36],
            [['attribute_uuid', 'option_uuid'], 'default', 'value' => null],
            [['title', 'alias', 'strategy_class'], 'string', 'max' => 255],
            [['uuid', 'alias'], 'unique'],
            [['attribute_uuid', 'strategy_class'], 'unique', 'targetAttribute' => ['attribute_uuid', 'strategy_class'], 'when' => function (Filter $model) {
                return $model->strategy == AttributeFilterStrategy::class;
            }],
            [['option_uuid', 'strategy_class'], 'unique', 'targetAttribute' => ['option_uuid', 'strategy_class'], 'when' => function (Filter $model) {
                return $model->strategy_class == OptionFilterStrategy::class;
            }],
            [['attribute_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Attribute::className(), 'targetAttribute' => ['attribute_uuid' => 'uuid'], 'when' => function (Filter $model) {
                return $model->strategy_class == AttributeFilterStrategy::class;
            }],
            [['option_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Option::className(), 'targetAttribute' => ['option_uuid' => 'uuid'], 'when' => function (Filter $model) {
                return $model->strategy_class == OptionFilterStrategy::class;
            }],
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
                    'optionModel',
                    'attributeModel',
                ],
            ],
            'filter' => [
                'class' => FilterBehavior::class
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
            'attribute_uuid' => 'Attribute Uuid',
            'option_uuid' => 'Option Uuid',
            'title' => 'Title',
            'alias' => 'Alias',
            'strategy_class' => 'Strategy',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AttributeModel]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\AttributeQuery
     */
    public function getAttributeModel()
    {
        return $this->hasOne(Attribute::className(), ['uuid' => 'attribute_uuid']);
    }

    /**
     * Gets query for [[OptionModel]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\OptionQuery
     */
    public function getOptionModel()
    {
        return $this->hasOne(Option::className(), ['uuid' => 'option_uuid']);
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\FilterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\FilterQuery(get_called_class());
    }

    /**
     * @param bool $combine
     * @return array
     */
    public static function strategyList($combine = false): array
    {
        $strategies = [
            AttributeFilterStrategy::class,
            OptionFilterStrategy::class
        ];

        if ($combine) {
            return array_combine($strategies, $strategies);
        }

        return $strategies;
    }
}
