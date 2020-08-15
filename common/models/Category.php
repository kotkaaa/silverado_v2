<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\classes\Optional\OptionalActiveRecordTrait;
use common\modules\File\behaviours\FileBehaviour;
use common\modules\File\storages\FieldStorage;
use common\modules\File\storages\LocalStorage;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "category".
 *
 * @property string $uuid
 * @property string|null $parent_uuid
 * @property string $title
 * @property string|null $description
 * @property string $alias
 * @property int $position
 * @property bool $active
 * @property string|null $icon
 * @property string|null $image
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $meta_robots
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Category $parent
 * @property Category[] $children
 */
class Category extends \yii\db\ActiveRecord
{

    use OptionalActiveRecordTrait;

    /** @var bool */
    public const ACTIVE_STATE_TRUE = true;
    public const ACTIVE_STATE_FALSE = false;

    /** @var UploadedFile */
    public $upload_icon;

    /** @var UploadedFile */
    public $upload_image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description', 'meta_description', 'meta_keywords'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['uuid', 'parent_uuid'], 'string', 'max' => 36],
            [['title', 'alias', 'icon', 'image', 'meta_title'], 'string', 'max' => 255],
            [['meta_robots'], 'string', 'max' => 32],
            [['position'], 'integer'],
            [['position'], 'default', 'value' => 1],
            [['active'], 'boolean'],
            [['active'], 'default', 'value' => true],
            [['active'], 'in', 'range' => self::activeStates(true)],
            [['alias'], 'unique'],
            [['uuid'], 'unique'],
            [['parent_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_uuid' => 'uuid']],
            [['upload_image', 'upload_icon'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg'], 'checkExtensionByMimeType' => true],
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
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'alias',
                'ensureUnique' => true,
                'immutable' => true
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
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'parent',
                    'children' => [
                        'cascadeDelete' => true
                    ]
                ],
            ],
            'icon' => [
                'class' => FileBehaviour::class,
                'field' => 'upload_icon',
                'multiple' => false,
                'storages' => [
                    LocalStorage::class => [
                        'path' => \yii\helpers\Url::to('@uploads/category')
                    ],
                    FieldStorage::class => [
                        'field' => 'icon'
                    ]
                ]
            ],
            'image' => [
                'class' => FileBehaviour::class,
                'field' => 'upload_image',
                'multiple' => false,
                'storages' => [
                    LocalStorage::class => [
                        'path' => \yii\helpers\Url::to('@uploads/category')
                    ],
                    FieldStorage::class => [
                        'field' => 'image'
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
            'parent_uuid' => 'Parent Uuid',
            'title' => 'Title',
            'description' => 'Description',
            'alias' => 'Alias',
            'position' => 'Position',
            'active' => 'Active',
            'icon' => 'Icon',
            'image' => 'Image',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'meta_robots' => 'Meta Robots',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\CategoryQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['uuid' => 'parent_uuid']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\CategoryQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Category::className(), ['parent_uuid' => 'uuid'])->ordered();
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\CategoryQuery(get_called_class());
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
