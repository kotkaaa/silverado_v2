<?php


namespace common\helpers;

use common\models\Files;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class FileUploadHelper
 * @package common\helpers
 */
class FileUploadHelper
{
    /**
     * @return string
     * @throws Exception
     */
    public static function initTempUploadPath() : string
    {
        $directory = \Yii::getAlias('@uploads/temp') . DIRECTORY_SEPARATOR . \Yii::$app->session->id;

        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory, 0777);
        }

        if (is_dir($directory)) {
            return $directory;
        }

        throw new Exception("Can not prepare upload path.");
    }

    /**
     * @return string
     */
    public static function initTempUploadUrl() : string
    {
        return \Yii::$app->params['frontUrl'] . '/uploads/temp/' . \Yii::$app->session->id . '/';
    }

    /**
     * @param Files $file
     * @param array $options
     * @param bool|array|null $label
     * @param bool|array|null $link
     * @param bool|array|null $enclose
     * @param bool|array|null $remove
     * @return string|null
     *
     * @example FileUploadHelper::renderUploadedFile($file, ['class' => 'ico'], ['tag' => 'span', 'options' => ['class' => 'text']], ['options' => ['target' => 'blank']], ['tag' => 'div', 'options' => ['class' => 'item']]
     */
    public static function renderUploadedFile(Files $file, $options = [], $label = null, $link = null, $enclose = null, $remove = null): ?string
    {
        if (!is_file($file->path . DIRECTORY_SEPARATOR . $file->name)) {
            return null;
        }

        $src = null;

        $mime = mime_content_type($file->path . DIRECTORY_SEPARATOR . $file->name);

        if (preg_match('/^image\/+/u', $mime)) {
            $src = Url::to( '/' . $file->url . '/' . $file->name);
        }
        
        if (!$src) {
            
            $ext = pathinfo($file->path . DIRECTORY_SEPARATOR . $file->name, PATHINFO_EXTENSION);

            if (preg_match('/^xls(x)?$/', $ext))
                $src = '/img/extensions/xls.png';
            else if (preg_match('/^csv$/', $ext))
                $src = '/img/extensions/csv.png';
            else if (preg_match('/^rtf$/', $ext))
                $src = '/img/extensions/rtf.png';
            else if (preg_match('/^doc(x)?$/', $ext))
                $src = '/img/extensions/doc.png';
            else if (preg_match('/^ppt(x)?$/', $ext))
                $src = '/img/extensions/ppt.png';
            else if (preg_match('/^pdf$/', $ext))
                $src = '/img/extensions/pdf.png';
            else if (preg_match('/^txt$/', $ext))
                $src = '/img/extensions/txt.png';
            else if (preg_match('/^svg$/', $ext))
                $src = '/img/extensions/svg.png';
            else if (preg_match('/^psd$/', $ext))
                $src = '/img/extensions/psd.png';
            else if (preg_match('/^zip$/', $ext))
                $src = '/img/extensions/zip.png';
            else if (preg_match('/^mp3$/', $ext))
                $src = '/img/extensions/mp3.png';
            else if (preg_match('/^mp4$/', $ext))
                $src = '/img/extensions/mp4.png';
            else if (preg_match('/^avi$/', $ext))
                $src = '/img/extensions/avi.png';
            else if (preg_match('/^json$/', $ext))
                $src = '/img/extensions/json.png';
            else
                $src = '/img/extensions/file.png';
        }

        $output = Html::img($src, $options);

        if ($link) {
            $output = Html::a($output, isset($link['href']) ? $link['href'] : $src, isset($link['options']) ? $link['options'] : []);
        }

        if ($label) {
            $output .= Html::tag(isset($label['tag']) ? $label['tag'] : 'span', isset($label['text']) ? $label['text'] : $file->name, isset($label['options']) ? $label['options'] : []);
        }

        if ($remove) {
            $output .= Html::tag(isset($remove['tag']) ? $remove['tag'] : 'button', isset($remove['text']) ? $remove['text'] : 'X', isset($remove['options']) ? $remove['options'] : []);
        }

        if ($enclose) {
            $output = Html::tag(isset($enclose['tag']) ? $enclose['tag'] : 'div', $output, isset($enclose['options']) ? $enclose['options'] : []);
        }

        return $output;
    }
}