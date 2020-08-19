<?php


namespace common\modules\File\behaviours;

use common\modules\File\exception\InvalidFileStorageException;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class AjaxFileBehaviour
 * @package common\modules\File\behaviours
 *
 * @property ActiveRecord $owner
 */
class AjaxFileBehaviour extends FileBehaviour
{
    /** @var string */
    public $tmpPath;

    /**
     * @throws InvalidFileStorageException
     */
    public function init()
    {
        if (!$this->tmpPath) {
            throw new InvalidFileStorageException('Tmp Path must be set!');
        }

        parent::init();
    }

    /**
     * @param Event $event
     * @return void
     */
    public function beforeValidate(Event $event): void
    {
        if (empty($this->owner->{$this->field})) {
            return;
        }

        $files = [];

        if ($this->multiple) {

            foreach ($this->owner->{$this->field} as $key => $name) {

                if ($name instanceof UploadedFile) {
                    return;
                }

                $tmpFile = $this->tmpPath . DIRECTORY_SEPARATOR . $name;

                if (is_file($tmpFile)) {
                    $files[] = new UploadedFile([
                        'tempName' => $tmpFile,
                        'name' => $name,
                        'size' => filesize($tmpFile),
                        'type' => mime_content_type($tmpFile)
                    ]);
                }

                unset($this->owner->{$this->field}[$key]);
            }
        } else {

            $name = $this->owner->{$this->field};

            if ($name instanceof UploadedFile) {
                return;
            }

            $tmpFile = $this->tmpPath . DIRECTORY_SEPARATOR . $name;

            if (is_file($tmpFile)) {
                $files = new UploadedFile([
                    'tempName' => $tmpFile,
                    'name' => $name,
                    'size' => filesize($tmpFile),
                    'type' => mime_content_type($tmpFile)
                ]);
            }
        }

        $this->owner->{$this->field} = $files;
    }

    /**
     * @param array $files
     * @return array
     */
    protected function preProcessFiles(array $files): array
    {
        $fileProcessed = [];

        foreach ($files as $file) {
            $file1 = clone $file;
            $file1->name = $file->baseName . '.' . $file->extension;
            $fileProcessed[] = $file1;
        }

        return $fileProcessed;
    }
}