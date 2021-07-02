<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "review_files".
 *
 * @property string $uuid
 * @property string|null $files_uuid
 * @property string|null $review_uuid
 * @property string|null $created_at
 *
 * @property Files $file
 * @property Review $review
 */
class ReviewFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['uuid', 'files_uuid', 'review_uuid'], 'string', 'max' => 36],
            [['uuid'], 'unique'],
            [['files_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Files::className(), 'targetAttribute' => ['files_uuid' => 'uuid']],
            [['review_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Review::className(), 'targetAttribute' => ['review_uuid' => 'uuid']],
        ];
    }

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => UuidBehavior::class,
                'version' => Uuid::V4,
                'defaultAttribute' => 'uuid',
            ],
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('now()'),
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
            'files_uuid' => 'Files Uuid',
            'review_uuid' => 'Review Uuid',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\FilesQuery
     */
    public function getFile()
    {
        return $this->hasOne(Files::className(), ['uuid' => 'files_uuid']);
    }

    /**
     * Gets query for [[Review]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ReviewQuery
     */
    public function getReview()
    {
        return $this->hasOne(Review::className(), ['uuid' => 'review_uuid']);
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\ReviewFilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ReviewFilesQuery(get_called_class());
    }
}
