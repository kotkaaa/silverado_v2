<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\behaviors\OptionBehavior;
use common\queries\OptionValueQuery;
use common\strategies\OptionPriceDecreaseStrategy;
use common\strategies\OptionPriceIncreaseStrategy;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "option".
 *
 * @property string $uuid
 * @property string $title
 * @property string|null $strategy
 * @property bool|null $required
 * @property int|null $position
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property OptionValue[] $values
 */
class Option extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['required'], 'boolean'],
            [['required'], 'default', 'value' => false],
            [['position'], 'default', 'value' => 1],
            [['position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uuid'], 'string', 'max' => 36],
            [['title'], 'string', 'max' => 255],
            [['strategy'], 'string', 'max' => 32],
            [['strategy'], 'in', 'range' => self::strategyList(true)],
            [['uuid'], 'unique'],
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
                    'values'
                ],
            ],
            'option' => [
                'class' => OptionBehavior::class
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
            'title' => 'Title',
            'strategy' => 'Strategy',
            'required' => 'Required',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return OptionValueQuery
     */
    public function getValues(): OptionValueQuery
    {
        return $this->hasMany(OptionValue::class, ['option_uuid' => 'uuid'])->ordered();
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\OptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\OptionQuery(get_called_class());
    }

    /**
     * @param bool $onlyKeys
     * @return array
     */
    public static function strategyList($onlyKeys = false): array
    {
        $strategies = [
            OptionPriceIncreaseStrategy::class => 'Increase Price',
            OptionPriceDecreaseStrategy::class => 'Decrease Price',
        ];

        if ($onlyKeys) {
            return array_keys($strategies);
        }

        return $strategies;
    }
}
