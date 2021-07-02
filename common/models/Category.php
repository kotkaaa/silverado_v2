<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\behaviors\CategoryBehavior;
use common\classes\Optional\OptionalActiveRecordTrait;
use common\models\interfaces\PrettyUrlModelInterface;
use common\modules\File\behaviours\FileBehavior;
use common\modules\File\storages\FieldStorage;
use common\modules\File\storages\LocalStorage;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Url;
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
 * @property bool $separator
 * @property string|null $redirect
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
class Category extends \yii\db\ActiveRecord implements PrettyUrlModelInterface
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
            [['parent_uuid'], 'default', 'value' => null],
            [['title', 'alias', 'icon', 'image', 'meta_title', 'redirect'], 'string', 'max' => 255],
            [['meta_robots'], 'string', 'max' => 32],
            [['position'], 'integer'],
            [['position'], 'default', 'value' => 1],
            [['active', 'separator'], 'boolean'],
            [['active'], 'default', 'value' => true],
            [['separator'], 'default', 'value' => false],
            [['active', 'separator'], 'in', 'range' => self::activeStates(true)],
            [['alias'], 'unique'],
            [['uuid'], 'unique'],
            [['upload_image', 'upload_icon'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg'], 'checkExtensionByMimeType' => true],
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
                    'parent',
                    'children' => [
                        'cascadeDelete' => true
                    ]
                ],
            ],
            'icon' => [
                'class' => FileBehavior::class,
                'field' => 'upload_icon',
                'multiple' => false,
                'storages' => [
                    LocalStorage::class => [
                        'path' => Url::to('@uploads/category')
                    ],
                    FieldStorage::class => [
                        'field' => 'icon'
                    ]
                ]
            ],
            'image' => [
                'class' => FileBehavior::class,
                'field' => 'upload_image',
                'multiple' => false,
                'storages' => [
                    LocalStorage::class => [
                        'path' => Url::to('@uploads/category')
                    ],
                    FieldStorage::class => [
                        'field' => 'image'
                    ]
                ]
            ],
            'category' => [
                'class' => CategoryBehavior::class,
                'uploadPath' => Url::to('@uploads/category')
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
            'parent_uuid' => 'Parent Uuid',
            'title' => 'Title',
            'description' => 'Description',
            'alias' => 'Alias',
            'position' => 'Position',
            'active' => 'Active',
            'separator' => 'Separator',
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
