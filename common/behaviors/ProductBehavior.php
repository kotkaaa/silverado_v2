<?php


namespace common\behaviors;

use common\models\AttributeValue;
use common\models\Files;
use common\models\OptionValue;
use common\models\Product;
use common\modules\File\exception\FileException;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class ProductBehavior
 * @package common\behaviors
 *
 * @property Product $owner
 */
class ProductBehavior extends \yii\base\Behavior
{
    /** @var string */
    public $uploadPath;

    /** @var bool */
    public $thumbnails;

    /**
     * @var array
     *
     * @example [
     *      'small' => [
     *          'w' => 120,
     *          'h' => 120
     *      ],
     *      'thumb' => [
     *          'w' => 90,
     *          'h' => 90
     *      ]
     * ]
     */
    public $thumbnailsParams;

    /**
     * @return array
     */
    public function events(): array
    {
        return [
            Product::EVENT_AFTER_FILE_UPLOAD => 'afterFileUpload',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    /**
     * @param Event $event
     * @return void
     */
    public function afterFind(Event $event): void
    {
        $this->owner->_attributes = ArrayHelper::getColumn($this->owner->productAttributes, 'value_uuid') ?? [];
        $this->owner->_options = ArrayHelper::getColumn($this->owner->productOptions, 'value_uuid') ?? [];
    }

    /**
     * @param Event $event
     * @return void
     */
    public function afterSave(Event $event): void
    {
        $this->owner->unlinkAll('productOptions', true);
        $this->owner->unlinkAll('productAttributes', true);

        foreach ($this->owner->_options as $uuid) {
            if (($model = OptionValue::findOne($uuid)) !== null) {
                $this->owner->link('optionValues', $model);
            }
        }

        foreach ($this->owner->_attributes as $uuid) {
            if (($model = AttributeValue::findOne($uuid)) !== null) {
                $this->owner->link('attributeValues', $model);
            }
        }
    }

    /**
     * @param Event $event
     * @return void
     */
    public function afterFileUpload(Event $event): void
    {
        $files = UploadedFile::getInstances($this->owner, 'upload');

        if (!$files) {
            return;
        }

        foreach ($files as $file) {

            $file->name = uniqid(Inflector::slug($file->baseName), true) . '.' . $file->extension;

            if (!$file->saveAs($this->uploadPath . DIRECTORY_SEPARATOR . $file->name)) {
                throw new FileException('File has not been uploaded.');
            }

            if ($this->thumbnails) {
                $this->generateThubnails($file);
            }

            $model = \Yii::createObject([
                'class' => Files::class,
                'name' => $file->name,
                'path' => Url::to('@uploads/product'),
                'url' => 'uploads/product'
            ]);

            if ($model->save()) {
                $this->owner->link('files', $model);
            }
        }
    }

    /**
     * @param UploadedFile $file
     */
    protected function generateThubnails(UploadedFile $file): void
    {
        foreach ($this->thumbnailsParams as $name => $params) {
            Image::thumbnail($this->uploadPath . DIRECTORY_SEPARATOR . $file->name, $params['w'], $params['h'])
                ->save($this->uploadPath . DIRECTORY_SEPARATOR . $name . '-' . $file->name, ['quality' => 80]);
        }
    }
}