<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

/**
 * This is the model class for table "product_files".
 *
 * @property string $uuid
 * @property string|null $files_uuid
 * @property string|null $product_uuid
 * @property int|null $position
 *
 * @property Files $files
 * @property Product $product
 */
class ProductFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['files_uuid', 'product_uuid'], 'required'],
            [['position'], 'default', 'value' => 1],
            [['position'], 'integer'],
            [['uuid', 'files_uuid', 'product_uuid'], 'string', 'max' => 36],
            [['uuid'], 'unique'],
            [['files_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['files_uuid' => 'uuid']],
            [['product_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_uuid' => 'uuid']],
        ];
    }

    /**
     * @inheritdoc
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
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'product',
                    'files' => [
                        'cascadeDelete' => true
                    ],
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
            'files_uuid' => 'Files Uuid',
            'product_uuid' => 'Product Uuid',
            'position' => 'Position',
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\FilesQuery
     */
    public function getFiles()
    {
        return $this->hasOne(Files::className(), ['uuid' => 'files_uuid']);
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
     * @return \common\queries\ProductFilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ProductFilesQuery(get_called_class());
    }
}
