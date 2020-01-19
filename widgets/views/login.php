<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Modal;
use app\widgets\Toggle;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\Login */

$js = <<< JS
$('#form-login').on('ajaxBeforeSend', function(e, x, s) { $('button[type="submit"]', this).prop('disabled', true).addClass('spin'); });
$('#form-login').on('ajaxComplete', function(e, x, t) { setTimeout(() => { $('button[type="submit"]', this).removeClass('spin').removeAttr('disabled'); }, 500); });
JS;

$this->registerJs($js, yii\web\View::POS_READY);
?>
<?php Modal::begin([
    'id' => 'modal-login',
    'title' => Yii::t('app', 'Авторизация'),
    'closeButton' => false,
]) ?>

<?php $form = ActiveForm::begin([
    'id' => 'form-login',
    'successCssClass' => 'valid',
    'errorCssClass' => 'invalid',
    'action' => ['site/login'],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnChange' => false,
    'fieldConfig' => [
        'template' => "{hint}\n{input}\n{label}\n{error}",
        'labelOptions' => ['class' => 'form-text'],
        'errorOptions' => ['class' => 'form-feedback'],
        'hintOptions' => ['class' => 'form-hint'],
        'enableLabel' => false,
    ],
]) ?>

<?= $form->field($model, 'username', [
    'options' => ['class' => 'wrap-field'],
    'inputOptions' => ['placeholder' => $model->getAttributeLabel('username')],
]) ?>

<?= $form->field($model, 'password', [
    'options' => ['class' => 'wrap-field'],
    'inputOptions' => ['placeholder' => $model->getAttributeLabel('password')],
])->passwordInput() ?>

<?= Toggle::widget(['model' => $model, 'attribute' => 'remember_me']) ?>

<div class="flex-end wrap-submit">
    <?= Html::button(Yii::t('app', 'Отмена'), ['class' => 'form-button muted', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton(Yii::t('app', 'Войти'), ['class' => 'form-button ml-2']) ?>
</div>

<?php ActiveForm::end() ?>

<?php Modal::end() ?>
