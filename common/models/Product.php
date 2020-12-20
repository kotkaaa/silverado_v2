<?php

namespace common\models;

use aracoool\uuid\Uuid;
use aracoool\uuid\UuidBehavior;
use common\behaviors\ProductBehavior;
use common\classes\Optional\OptionalActiveRecordTrait;
use common\models\interfaces\PrettyUrlModelInterface;
use common\queries\AttributeQuery;
use common\queries\AttributeValueQuery;
use common\queries\OptionQuery;
use common\queries\OptionValueQuery;
use common\queries\ProductAttributeQuery;
use common\queries\ProductFilesQuery;
use common\queries\ProductOptionQuery;
use common\queries\ProductQuery;
use common\queries\ProductSetQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii2mod\cart\models\CartItemInterface;

/**
 * This is the model class for table "product".
 *
 * @property string $uuid
 * @property string $sku
 * @property string|null $category_uuid
 * @property string $title
 * @property string|null $description
 * @property string|null $short
 * @property float|null $price
 * @property int|null $discount
 * @property int|null $viewed
 * @property int|null $purchased
 * @property int|null $rating
 * @property int|null $position
 * @property bool $active
 * @property string $alias
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $meta_robots
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Category $category
 * @property ProductFiles[] $productFiles
 * @property Files[] $files
 * @property ProductOption[] $productOptions
 * @property OptionValue[] $optionValues
 * @property Option[] $options
 * @property ProductAttribute[] $productAttributes
 * @property AttributeValue[] $attrbuteValues
 * @property Attribute[] $attributeModels
 * @property ProductSet[] $productSet
 * @property Product[] $set
 * @property Product $master
 */
class Product extends \yii\db\ActiveRecord implements PrettyUrlModelInterface, CartItemInterface
{
    use OptionalActiveRecordTrait;

    /** @var bool */
    public const ACTIVE_STATE_TRUE = true;
    public const ACTIVE_STATE_FALSE = false;

    /** @var string */
    public const EVENT_AFTER_FILE_UPLOAD = 'afterFileUpload';

    /** @var UploadedFile[] */
    public $upload;

    /** @var array */
    public $_options;

    /** @var array */
    public $selectedOptions;

    /** @var array */
    public $_attributes;

    /** @var array */
    public $_set;

    /** @var Files */
    public $_preview;

    /** @var int */
    public $quantity = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku', 'title'], 'required'],
            [['description', 'short', 'meta_description', 'meta_keywords'], 'string'],
            [['price'], 'number'],
            [['price'], 'default', 'value' => 0.00],
            [['discount', 'viewed', 'purchased', 'rating', 'position'], 'integer'],
            [['discount', 'viewed', 'purchased', 'rating'], 'default', 'value' => null],
            [['position'], 'default', 'value' => 1],
            [['created_at', 'updated_at', '_options', '_attributes', '_set', 'selectedOptions'], 'safe'],
            [['_options', '_attributes', '_set'], 'default', 'value' => []],
            [['uuid', 'category_uuid'], 'string', 'max' => 36],
            [['sku'], 'string', 'max' => 32],
            [['active'], 'boolean'],
            [['active'], 'default', 'value' => true],
            [['active'], 'in', 'range' => self::activeStates(true)],
            [['title', 'alias', 'meta_title'], 'string', 'max' => 255],
            [['meta_robots'], 'string', 'max' => 32],
            [['sku', 'uuid'], 'unique'],
            [['category_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_uuid' => 'uuid']],
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
                'attribute' => 'sku',
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
                    'category',
                    'productOptions',
                    'productAttributes',
                    'optionValues',
                    'attributeValues',
                    'productSet',
                    'productFiles' => [
                        'cascadeDelete' => true
                    ],
                ],
            ],
            'product' => [
                'class' => ProductBehavior::class,
                'uploadPath' => Url::to('@uploads/product'),
                'thumbnails' => true,
                'thumbnailsParams' => [
                    'full' => [
                        'w' => 960,
                        'h' => 960
                    ],
                    'preview' => [
                        'w' => 560,
                        'h' => 560
                    ],
                    'middle' => [
                        'w' => 280,
                        'h' => 280
                    ],
                    'small' => [
                        'w' => 110,
                        'h' => 110
                    ],
                    'thumb' => [
                        'w' => 90,
                        'h' => 90
                    ]
                ]
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
            'sku' => 'Sku',
            'category_uuid' => 'Category Uuid',
            'title' => 'Title',
            'description' => 'Description',
            'short' => 'Short',
            'price' => 'Price',
            'discount' => 'Discount',
            'viewed' => 'Viewed',
            'purchased' => 'Purchased',
            'rating' => 'Rating',
            'position' => 'Position',
            'active' => 'Active',
            'alias' => 'Alias',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'meta_robots' => 'Meta Robots',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery|\common\queries\CategoryQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['uuid' => 'category_uuid']);
    }

    /**
     * @return ProductFilesQuery
     */
    public function getProductFiles(): ProductFilesQuery
    {
        return $this->hasMany(ProductFiles::class, ['product_uuid' => 'uuid'])->ordered();
    }

    /**
     * @return ActiveQuery
     */
    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(Files::class, ['uuid' => 'files_uuid'])
            ->via('productFiles')
            ->orderBy(new Expression('position(uuid::text in \'' . implode(',', ArrayHelper::getColumn($this->productFiles, 'files_uuid')) . '\')'));
    }

    /**
     * @return ProductOptionQuery
     */
    public function getProductOptions(): ProductOptionQuery
    {
        return $this->hasMany(ProductOption::class, ['product_uuid' => 'uuid']);
    }

    /**
     * @return OptionValueQuery
     */
    public function getOptionValues(): OptionValueQuery
    {
        return $this->hasMany(OptionValue::class, ['uuid' => 'value_uuid'])->via('productOptions');
    }

    /**
     * @return OptionQuery
     */
    public function getOptions(): OptionQuery
    {
        return $this->hasMany(Option::class, ['uuid' => 'option_uuid'])->via('optionValues');
    }

    /**
     * @return ProductAttributeQuery
     */
    public function getProductAttributes(): ProductAttributeQuery
    {
        return $this->hasMany(ProductAttribute::class, ['product_uuid' => 'uuid']);
    }

    /**
     * @return AttributeValueQuery
     */
    public function getAttributeValues(): AttributeValueQuery
    {
        return $this->hasMany(AttributeValue::class, ['uuid' => 'value_uuid'])->via('productAttributes');
    }

    /**
     * @return AttributeQuery
     */
    public function getAttributeModels(): AttributeQuery
    {
        return $this->hasMany(Attribute::class, ['uuid' => 'attribute_uuid'])->via('attributeValues');
    }

    /**
     * @return ProductSetQuery
     */
    public function getProductSet(): ProductSetQuery
    {
        return $this->hasMany(ProductSet::class, ['master_uuid' => 'uuid']);
    }

    /**
     * @return ProductQuery
     */
    public function getSet(): ProductQuery
    {
        return $this->hasMany(Product::class, ['uuid' => 'slave_uuid'])->via('productSet');
    }

    /**
     * @return ProductQuery
     */
    public function getMaster(): ProductQuery
    {
        return $this->hasOne(Product::class, ['uuid' => 'master_uuid'])->viaTable('product_set', ['slave_uuid' => 'uuid']);
    }

    /**
     * @return float|int|null
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int|string
     */
    public function getLabel()
    {
        return $this->title;
    }

    /**
     * @return int|string
     */
    public function getUniqueId()
    {
        if ($this->selectedOptions) {
            return md5($this->uuid . serialize($this->selectedOptions));
        }

        return $this->uuid;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * {@inheritdoc}
     * @return \common\queries\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\queries\ProductQuery(get_called_class());
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
