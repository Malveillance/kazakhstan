<?php

use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\Dropdown;
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;
use app\widgets\Modal;

/* @var $this yii\web\View */
/* @var $content string */

app\assets\AppAsset::register($this);
app\assets\FontsAsset::register($this);

$username = Yii::$app->user->isGuest ? Yii::t('app', 'Гость') : Yii::$app->user->identity->username;

$js = <<< JS
$(function() { $('[data-toggle="tooltip"]').data('template', '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>').data('offset', '0, 3, 0, 0').tooltip(); });
$('.dropdown').on('show.bs.dropdown', function() { $('[data-toggle="tooltip"]').tooltip('hide'); });
$('.modal').on('show.bs.modal', function() { $('[data-toggle="tooltip"]').tooltip('hide'); });
JS;

$this->registerJs($js, yii\web\View::POS_READY);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title . ' – ' . Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="header">
    <div class="container">
        <nav class="navbar navbar-expand-sm">
            <?= Html::a(Yii::$app->name, Yii::$app->homeUrl, ['class' => 'navbar-brand d-none d-sm-block']) ?>
            <?= Html::button('<span class="navbar-toggler-icon"></span>', ['class' => 'navbar-toggler', 'data-toggle' => 'collapse', 'data-target' => '#menu-collapse', 'aria-controls' => 'menu-collapse', 'aria-expanded' => 'false']) ?>

            <?php if (Yii::$app->user->isGuest): ?>
                <div class="order-sm-2">
                    <span data-toggle="modal" data-target="#modal-login">
                        <?= Html::button('<i class="fa fa-sign-in" aria-hidden="true"></i>', ['class' => 'round-button', 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-original-title' => Yii::t('app', 'Войти')]) ?>
                    </span>
                </div>
            <?php else: ?>
                <div class="dropdown order-sm-2">
                    <span data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= Html::button(mb_substr($username, 0, 1) ?: '?', ['class' => 'round-button user', 'data-toggle' => 'tooltip', 'data-placement' => 'left', 'data-original-title' => Yii::t('app', 'Вы вошли как {u}', ['u' => $username])]) ?>
                    </span>
                    <?= Dropdown::widget([
                        'id' => false,
                        'options' => ['class' => 'dropdown-menu-right'],
                        'items' => [
                            Html::button(Yii::t('app', 'Сменить пароль'), ['class' => 'dropdown-item', 'data-toggle' => 'modal', 'data-target' => '#modal-password']),
                            Html::beginForm(['/site/logout'], 'post') . Html::submitButton(Yii::t('app', 'Выйти'), ['class' => 'dropdown-item']) . Html::endForm(),
                        ],
                    ]) ?>
                </div>
            <?php endif ?>

            <div id="menu-collapse" class="collapse navbar-collapse order-sm-1">
                <?= Nav::widget([
                    'id' => false,
                    'options' => ['class' => 'navbar-nav'],
                    'items' => [
                        ['label' => Yii::t('app', 'Оборудование'), 'url' => ['machine/list']],
                    ],
                ]) ?>
            </div>
        </nav>
    </div>
</div>

<div class="content spacer">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink' => false,
            'itemTemplate' => '{link}',
            'activeItemTemplate' => '{link}',
            'tag' => 'div',
            'options' => ['class' => 'breadcrumbs'],
            'encodeLabels' => false,
        ]) ?>

        <?= Alert::widget() ?>

        <div class="<?= Yii::$app->controller->id . '-' . Yii::$app->controller->action->id ?> wrap">
            <?= $content ?>
        </div>
    </div>
</div>

<div class="footer">
    <div class="container">
        <p><?= date('Y') . ' ' . Yii::$app->name ?></p>
    </div>
</div>

<?php if (Yii::$app->user->isGuest) {
    echo Modal::widget(['model' => 'app\models\Login', 'view' => 'login']);
} else {
    echo Modal::widget(['model' => 'app\models\Password', 'view' => 'password']);
} ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
