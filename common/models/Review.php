<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\modules\File\behaviours\FileBehavior;
use common\modules\File\storages\LocalStorage;
use common\modules\File\storages\RelationStorage;
use common\queries\ReviewFilesQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "review".
 *
 * @property string $uuid
 * @property string|null $author
 * @property string|null $comment
 * @property int|null $rate
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property ProductReview[] $productReviews
 * @property ReviewFiles[] $reviewFiles
 * @property Files[] $files
 */
class Review extends \yii\db\ActiveRecord
{
    /** @var string */
    public const SCENARIO_POST = 'post';

    /** @var UploadedFile[] */
    public $_upload;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author', 'comment'], 'required'],
            [['comment', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['rate'], 'default', 'value' => null],
            [['rate'], 'integer', 'min' => 1, 'max' => 5],
            [['comment'], 'string', 'min' => 10, 'max' => 1000],
            [['uuid'], 'string', 'max' => 36],
            [['author'], 'string', 'max' => 255],
            [['uuid'], 'unique'],
            [['_upload'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'heic'], 'checkExtensionByMimeType' => true],
        ];
    }

    /**
     * @return array
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('now()'),
            ],
            [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'deleted_at' => new Expression('now()'),
                ],
                'replaceRegularDelete' => true
            ],
            [
                'class' => FileBehavior::class,
                'field' => '_upload',
                'multiple' => false,
                'storages' => [
                    LocalStorage::class => [
                        'path' => Url::to('@uploads/review')
                    ],
                    RelationStorage::class => [
                        'strategy' => RelationStorage::STRATEGY_ADD,
                        'relations' => [
                            'files' => function (UploadedFile $file): ActiveRecordInterface {
                                return \Yii::createObject([
                                    'class' => Files::class,
                                    'name' => $file->name,
                                    'path' => Url::to('@uploads/review'),
                                    'url' => 'uploads/review'
                                ]);
                            }
                        ]
                    ]
                ]
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
            'author' => 'Автор',
            'comment' => 'Коментар',
            'rate' => 'Rate',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[ProductReviews]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\ProductReviewQuery
     */
    public function getProductReviews()
    {
        return $this->hasMany(ProductReview::className(), ['review_uuid' => 'uuid']);
    }

    /**
     * @return \yii\db\ActiveQuery|ReviewFilesQuery
     */
    public function getReviewFiles()
    {
        return $this->hasMany(ReviewFiles::class, ['review_uuid' => 'uuid'])->ordered();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::class, ['uuid' => 'files_uuid'])->via('reviewFiles');
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\ReviewQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ReviewQuery(get_called_class());
    }
}
