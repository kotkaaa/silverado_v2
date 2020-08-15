<?php

namespace common\modules\File\components;

use common\modules\File\assets\FileFieldAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveField;

/**
 * Class FileActiveField
 * @package common\modules\File\components
 */
class FileActiveField extends ActiveField
{

    public function init()
    {
        FileFieldAsset::register($this->form->getView());
        parent::init();
    }

    /**
     * @param array $options
     * @return string
     */
    public function fileTextarea($options = [])
    {

        $pluginOptions = $options['pluginOptions'] ?? [];
        $pluginOptions['container'] = $pluginOptions['container'] ?? Html::getInputId($this->model, $this->attribute) . '-container';
        $this->setOptions($pluginOptions);

        $name = Html::getInputName($this->model, $this->attribute);
        $fileName = str_replace($this->attribute, $this->attribute . '_files', $name);

        $data = parent::textarea($options);

        $data->parts['{input}'] = '<div id="' . $pluginOptions['container'] . '">' . $data->parts['{input}'] . '<div class="file-input"><label class="input-wrap">'
            . Html::fileInput($fileName . '[]', null, ['multiple' => 'true']) . '<span class="file-input__text">Прикрепить</span></label></div></div>';

        return $data;
    }

    /**
     * @param array $items
     * @param array $options
     * @return $this|ActiveField
     */
    public function fileCheckboxList($items, $options = [])
    {

        $pluginOptions = $options['pluginOptions'] ?? [];
        $pluginOptions['container'] = $pluginOptions['container'] ?? Html::getInputId($this->model, $this->attribute);
        $this->setOptions($pluginOptions);

        if (!isset($options['item'])) {
            $options['item'] = function ($index, $label, $name, $checked, $value) use ($options) {
                $options = array_merge([
                    'label' => $label,
                    'value' => $value
                ], $options['itemOptions'] ?? []);

                $fileName = str_replace($this->attribute, $this->attribute . '_files', $name);
                return '<div class="checkbox-list__row file-input">' . Html::checkbox($name, $checked, $options) . '<label class="input-wrap">'
                    . Html::fileInput($fileName . '[]', null, ['multiple' => 'true']) . '<span class="file-input__text">Прикрепить</span></label></div>';
            };
        }

        return parent::checkboxList($items, $options);
    }

    /**
     * @param array $options
     */
    private function setOptions(array $options = [])
    {
        $this->form->getView()->registerJs(
            "new FileField(" . \yii\helpers\Json::htmlEncode($options) . ").init();",
            View::POS_END,
            'fileFieldOptions'
        );

    }



}
