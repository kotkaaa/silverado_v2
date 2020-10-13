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
 * This is the model class for table "option_value".
 *
 * @property string $uuid
 * @property string $option_uuid
 * @property string $title
 * @property string $alias
 * @property float|null $price
 * @property string|null $action
 * @property int|null $position
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Option $option
 */
class OptionValue extends \yii\db\ActiveRecord
{
    use OptionalActiveRecordTrait;

    /** @var string */
    public const STRATEGY_ACTION_INCREASE = 'increase';
    public const STRATEGY_ACTION_DECREASE = 'decrease';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'option_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['option_uuid', 'title'], 'required'],
            [['price'], 'number'],
            [['position'], 'default', 'value' => null],
            [['position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uuid', 'option_uuid'], 'string', 'max' => 36],
            [['title', 'alias'], 'string', 'max' => 255],
            [['action'], 'string', 'max' => 32],
            [['action'], 'default', 'value' => null],
            [['action'], 'in', 'range' => self::actionsList()],
            [['uuid', 'alias'], 'unique'],
            [['option_uuid', 'title'], 'unique', 'targetAttribute' => ['option_uuid', 'title']],
            [['option_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Option::className(), 'targetAttribute' => ['option_uuid' => 'uuid']],
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
                    'option'
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
            'option_uuid' => 'Option Uuid',
            'title' => 'Title',
            'alias' => 'Alias',
            'price' => 'Price',
            'action' => 'Action',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Option]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\OptionQuery
     */
    public function getOption()
    {
        return $this->hasOne(Option::className(), ['uuid' => 'option_uuid']);
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\OptionValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\OptionValueQuery(get_called_class());
    }

    /**
     * @param bool $combine
     * @return array
     */
    public static function actionsList($combine = false): array
    {
        $actions = [
            self::STRATEGY_ACTION_INCREASE,
            self::STRATEGY_ACTION_DECREASE
        ];

        if ($combine) {
            return array_combine($actions, $actions);
        }

        return $actions;
    }
}
