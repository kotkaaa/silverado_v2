<?php

namespace common\models;

use common\queries\OrderInfoQuery;
use common\queries\OrderProductQuery;
use common\queries\OrderQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $status
 * @property string $source
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property OrderInfo $orderInfo
 * @property OrderProduct[] $orderProducts
 */
class Order extends \yii\db\ActiveRecord
{
    /** @var string */
    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /** @var string */
    public const SOURCE_SITE = 'site';
    public const SOURCE_INSTAGRAM = 'instagram';

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
            [['status', 'source'], 'string'],
            [['status', 'source'], 'required'],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['source'], 'default', 'value' => self::SOURCE_SITE],
            [['status'], 'in', 'range' => self::statusList(true)],
            [['source'], 'in', 'range' => self::sourceList(true)],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
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
                    'orderInfo',
                    'orderProducts'
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
            'id' => 'ID',
            'status' => 'Status',
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

    /**
     * @param bool $onlyKeys
     * @return array
     */
    public static function statusList($onlyKeys = false): array
    {
        $list = [
            self::STATUS_NEW => 'Новый',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Выполнен',
            self::STATUS_CANCELLED => 'Отменен'
        ];

        if ($onlyKeys) {
            return array_keys($list);
        }

        return $list;
    }

    /**
     * @param bool $onlyKeys
     * @return array
     */
    public static function sourceList($onlyKeys = false): array
    {
        $list = [
            self::SOURCE_SITE => 'Сайт',
            self::SOURCE_INSTAGRAM => 'Instagram'
        ];

        if ($onlyKeys) {
            return array_keys($list);
        }

        return $list;
    }
}
