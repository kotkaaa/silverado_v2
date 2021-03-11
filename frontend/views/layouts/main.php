<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\widgets\menu\MainMenu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">

            <?= $this->render('_header') ?>

            <div class="container-fluid main-container">

<?php if (\Yii::$app->cart->getCount()): ?>

        <?= \yii2mod\cart\widgets\CartGrid::widget([
            'cartColumns' => [
                'label',
                'price',
                'quantity',
                [
                    'label' => 'Total',
                    'value' => function (\yii2mod\cart\models\CartItemInterface $model) {
                        return $model->getPrice() * $model->quantity;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function($url, \yii2mod\cart\models\CartItemInterface $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/cart/remove', 'id' => $model->getUniqueId()], [
                                'title' => 'Remove',
                            ]);
                        }
                    ]
                ]
            ]
        ]) ?>

        <p>
            <?= Html::a('Оформить заказ', ['/cart'], ['class' => 'btn btn-success btn-lg']) ?>
            <?= Html::a('Очистить корзину', ['cart/clear'], ['class' => 'btn btn-default btn-lg']) ?>
        </p>

<?php endif;?>

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= Alert::widget() ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
