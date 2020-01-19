<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\Password */

$js = <<< JS
$('#form-password').on('ajaxBeforeSend', function(e, x, s) { $('button[type="submit"]', this).prop('disabled', true).addClass('spin'); });
$('#form-password').on('ajaxComplete', function(e, x, t) { setTimeout(() => { $('button[type="submit"]', this).removeClass('spin').removeAttr('disabled'); }, 500); });
JS;

$this->registerJs($js, yii\web\View::POS_READY);
?>
<?php Modal::begin([
    'id' => 'modal-password',
    'title' => Yii::t('app', 'Смена пароля'),
    'closeButton' => false,
]) ?>

<?php $form = ActiveForm::begin([
    'id' => 'form-password',
    'successCssClass' => 'valid',
    'errorCssClass' => 'invalid',
    'action' => ['user/change-password'],
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

<?= $form->field($model, 'current', [
    'options' => ['class' => 'wrap-field'],
    'inputOptions' => ['placeholder' => $model->getAttributeLabel('current')],
])->passwordInput() ?>

<?= $form->field($model, 'new', [
    'options' => ['class' => null],
    'inputOptions' => ['placeholder' => $model->getAttributeLabel('new')],
])->passwordInput() ?>

<div class="flex-end wrap-submit">
    <?= Html::button(Yii::t('app', 'Отмена'), ['class' => 'form-button muted', 'data-dismiss' => 'modal']) ?>
    <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'form-button ml-2']) ?>
</div>

<?php ActiveForm::end() ?>

<?php Modal::end() ?>
