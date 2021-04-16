<?php

use yii\bootstrap\Modal;

/** @var string $modal_id */
/** @var string|null $header */
/** @var string|null $content */
/** @var \yii\web\View $this */

\frontend\widgets\modal\assets\MainModalAsset::register($this);
?>

<?php Modal::begin(['id' => $modal_id, 'header' => $header]) ?>
    <?= $content ?>
<?php Modal::end() ?>
