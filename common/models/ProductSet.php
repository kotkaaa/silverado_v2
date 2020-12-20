<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;

/**
 * This is the model class for table "product_set".
 *
 * @property string $uuid
 * @property string $master_uuid
 * @property string $slave_uuid
 *
 * @property Product $master
 * @property Product $slave
 */
class ProductSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_set';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['master_uuid', 'slave_uuid'], 'required'],
            [['uuid', 'master_uuid', 'slave_uuid'], 'string', 'max' => 36],
            [['uuid'], 'unique'],
            [['master_uuid', 'slave_uuid'], 'unique', 'targetAttribute' => ['master_uuid', 'slave_uuid']],
            [['master_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['master_uuid' => 'uuid']],
            [['slave_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['slave_uuid' => 'uuid']],
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
            'master_uuid' => 'Master Uuid',
            'slave_uuid' => 'Slave Uuid',
        ];
    }

    /**
     * Gets query for [[Master]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ProductQuery
     */
    public function getMaster()
    {
        return $this->hasOne(Product::className(), ['uuid' => 'master_uuid']);
    }

    /**
     * Gets query for [[Slave]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ProductQuery
     */
    public function getSlave()
    {
        return $this->hasOne(Product::className(), ['uuid' => 'slave_uuid']);
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\ProductSetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ProductSetQuery(get_called_class());
    }
}
