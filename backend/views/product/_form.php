<?php

use common\helpers\MetaHelper;
use dosamigos\fileupload\FileUpload;
use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $categoryTree \common\models\Category[] */
/* @var $options \common\models\Option[] */
/* @var $attributes \common\models\Attribute[] */
/* @var $form yii\widgets\ActiveForm */

$js = <<<JS
    FileUpload.init({
        namespace: 'ProductFiles[]'
    });
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
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

    <?= FileUpload::widget([
        'model' => $model,
        'attribute' => 'upload[]',
        'url' => Url::to(['file-upload', 'id' => $model->uuid]),
        'options' => [
            'multiple' => 'multiple'
        ],
        'clientOptions' => [
            'autoUpload' => true,
            'singleFileUploads' => false,
            'maxFileSize' => 5000000,
            'dataType' => 'json'
        ],
        'clientEvents' => [
//            'fileuploaddone' => 'window.location.reload()',
            'fileuploadstart' => 'FileUpload.start',
            'fileuploaddone' => 'FileUpload.done',
            'fileuploadfail' => 'FileUpload.stop',
            'fileuploadprogress' => 'FileUpload.progress',
        ],
    ]) ?>

    <br><br>

    <div class="progress">
        <div class="progress-bar progress-bar-info progress-bar-striped hidden" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
        </div>
    </div>

    <table role="presentation" class="table table-striped">
        <thead>
            <tr>
                <th>Preview</th>
                <th>Name</th>
                <th>Size (bytes)</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="files" id="uploaded_files">
<?php foreach ($model->productFiles as $productFile): ?>
            <tr>
                <td>
                    <?php Modal::begin([
                        'toggleButton' => [
                            'tag' => 'a',
                            'label' => Html::tag('object', '', ['data' => implode('/', [\Yii::$app->params['frontUrl'], $productFile->files->url, $productFile->files->name]), 'type' => $productFile->files->mime, 'class' => 'img-thumbnail', 'width' => 128, 'height' => 128])
                        ]
                    ]) ?>
                    <?= Html::tag('object', '', ['data' => implode('/', [\Yii::$app->params['frontUrl'], $productFile->files->url, $productFile->files->name]), 'type' => $productFile->files->mime, 'class' => 'img-thumbnail']) ?>
                    <?php Modal::end() ?>
                </td>
                <td>
                    <?= $productFile->files->name ?>
                    <?= Html::hiddenInput('ProductFiles[][uuid]', $productFile->uuid) ?>
                </td>
                <td>
                    <?= $productFile->files->size ?>
                </td>
                <td>
                    <?= Html::a('Delete', ['delete-uploaded-file', 'id' => $productFile->uuid], ['class' => 'btn btn-danger']) ?>
                </td>
            </tr>
<?php endforeach;?>
        </tbody>
    </table>

<?php endif;?>
    <hr>

    <h3>Price</h3>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <hr>

    <h3>Options</h3>

<?php foreach ($options as $option): ?>
    <?php if(empty($option->values)) continue; ?>
    <?= $form->field($model, '_options')->checkboxList(ArrayHelper::map($option->values, 'uuid', 'title'), ['unselect' => null])->label($option->title) ?>
<?php endforeach;?>

    <hr>

    <h3>Attributes</h3>

<?php foreach ($attributes as $attribute): ?>
    <?php if(empty($attribute->values)) continue; ?>
    <?= $form->field($model, '_attributes')->checkboxList(ArrayHelper::map($attribute->values, 'uuid', 'title'), ['unselect' => null])->label($attribute->title) ?>
<?php endforeach;?>

    <hr>

    <h3>Equipment</h3>

    <?= $form->field($model, '_set')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->set, 'uuid', 'title'),
        'value' => ArrayHelper::map($model->set, 'uuid', 'title'),
        'options' => [
            'multiple' => true,
            'placeholder' => '-- Select --',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Url::to('/product/search'),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {term:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(result) { return result.text; }'),
            'templateSelection' => new JsExpression('function (result) { return result.text; }'),
        ],
    ])->label(false) ?>

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