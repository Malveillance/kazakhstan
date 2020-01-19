<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Machine */

$this->title = $model->name;

$tDelete = Yii::t('app', 'Удалить');
$tConfirm = Yii::t('app', 'Вы уверены?');

$js = <<< JS
$('#delete-button').click(function(e) { e.preventDefault(); if ($(this).hasClass('confirm')) $.post($(this).attr('href')); $(this).text('$tConfirm').addClass('confirm'); });
$('#delete-button').focusout(function() { $(this).text('$tDelete').removeClass('confirm'); });
JS;

$this->registerJs($js, yii\web\View::POS_READY);

$this->params['breadcrumbs'][] = ['label' => '<span class="d-none d-sm-block">' . Yii::t('app', 'Просмотр оборудования') . '<i class="fa fa-chevron-right"></i></span>'];
$this->params['breadcrumbs'][] = ['label' => '<span class="id">#' . ($model->id) . '</span>'];

if (!Yii::$app->user->isGuest) {
    $this->params['breadcrumbs'][] = ['label' => '<span class="spacer"></span>'];
    $this->params['breadcrumbs'][] = ['label' => Html::a(Yii::t('app', 'Добавить'), ['add'], ['role' => 'button', 'class' => 'bread-button'])];
    $this->params['breadcrumbs'][] = ['label' => '<span class="bullet">•</span>'];
    $this->params['breadcrumbs'][] = ['label' => Html::a(Yii::t('app', 'Изменить'), ['edit', 'id' => $model->id], ['role' => 'button', 'class' => 'bread-button'])];
    $this->params['breadcrumbs'][] = ['label' => '<span class="bullet">•</span>'];
    $this->params['breadcrumbs'][] = ['label' => Html::a($tDelete, ['delete', 'id' => $model->id], ['id' => 'delete-button', 'role' => 'button', 'class' => 'bread-button'])];
}
?>
<?= Html::tag('h2', Html::encode($this->title)) ?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'draft',
        'name',
        'manufacturer',
        'manufacturer_country',
        'manufacturer_url:url',
        'agent',
        'agent_url:url',
        'process',
        'build_platform_d',
        'build_platform_x',
        'build_platform_y',
        'build_platform_z',
        'build_heat',
        'build_heat_t_max',
        'build_heat_desc:ntext',
        'laser_type',
        'laser_count',
        'laser1_power',
        'laser1_d',
        'laser1_wl',
        'laser2_power',
        'laser2_d',
        'laser2_wl',
        'layer_thickness_min',
        'layer_thickness_max',
        'scan_speed_max',
        'dimension_l',
        'dimension_w',
        'dimension_h',
        'weight',
        'dimension_inst_l',
        'dimension_inst_w',
        'dimension_inst_h',
        'dimension_tran_l',
        'dimension_tran_w',
        'dimension_tran_h',
        'gas_type',
        'cnc_system',
    ],
]) ?>
