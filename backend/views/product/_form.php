<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\MetaHelper;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $categoryTree \common\models\Category[] */
/* @var $options \common\models\Option[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin([
        'validateOnSubmit' => true,
        'options' => [
            'onsubmit' => new JsExpression('function(form) {
                for(var instanceName in CKEDITOR.instances) { 
                    CKEDITOR.instances[instanceName].updateElement();
                } return true;
            }'),
        ]
    ]) ?>

    <?= $this->render('_category-tree', [
        'categoryTree' => $categoryTree,
        'model' => $model,
        'level' => 0,
    ]) ?>

    <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput([
        'readonly' => true,
        'ondblclick' => new JsExpression('function(){this.readonly = false;}')
    ]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => [
            'rows' => 16
        ],
        'editorOptions' => ElFinder::ckeditorOptions([
            'elfinder',
            'path' => '/content',
            'filter' => 'image',
            'onlyMimes' => ["image"],
            'toolbar' => [
                ['back', 'forward', 'upload', 'download', 'info', 'quicklook'],
                ['rm'],
                ['duplicate', 'rename',],
                ['extract', 'archive'],
                ['search'],
                ['view'],
                ['help']
            ],
        ])
    ]) ?>

    <?= $form->field($model, 'short')->textarea(['rows' => 6]) ?>

<?php if($model->uuid): ?>
    <hr>

    <h3>Images</h3>

    <?= FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'upload',
        'url' => Url::to(['file-upload', 'id' => $model->uuid]),
        'load' => true,
        'options' => [
            'accept' => 'image/*',
            'multiple' => 'multiple'
        ],
        'clientOptions' => [
            'autoUpload' => false,
            'singleFileUploads' => false,
            'maxFileSize' => 30000000,
            'dataType' => 'json'
        ],
        'clientEvents' => [
//            'fileuploaddone' => 'FileUpload.done',
//            'fileuploadfail' => 'FileUpload.stop',
        ],
    ]) ?>
<?php endif;?>
    <hr>

    <h3>Price</h3>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <hr>

    <h3>Options</h3>

<?php foreach ($options as $option): ?>
    <?= $form->field($model, '_options')->checkboxList(ArrayHelper::map($option->values, 'uuid', 'title'))->label($option->title) ?>
<?php endforeach;?>

    <hr>

    <h3>Settings</h3>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <hr>

    <h3>META data</h3>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_robots')->dropDownList(MetaHelper::metaRobots(true), ['prompt' => '-- Select --']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
