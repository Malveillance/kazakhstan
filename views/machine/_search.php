<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MachineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machines-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'draft') ?>

    <?= $form->field($model, 'model') ?>

    <?= $form->field($model, 'manufacturer') ?>

    <?= $form->field($model, 'manufacturer_country') ?>

    <?php // echo $form->field($model, 'manufacturer_url') ?>

    <?php // echo $form->field($model, 'agent') ?>

    <?php // echo $form->field($model, 'agent_url') ?>

    <?php // echo $form->field($model, 'process') ?>

    <?php // echo $form->field($model, 'build_platform_d') ?>

    <?php // echo $form->field($model, 'build_platform_x') ?>

    <?php // echo $form->field($model, 'build_platform_y') ?>

    <?php // echo $form->field($model, 'build_platform_z') ?>

    <?php // echo $form->field($model, 'build_heat') ?>

    <?php // echo $form->field($model, 'build_heat_t_max') ?>

    <?php // echo $form->field($model, 'build_heat_desc') ?>

    <?php // echo $form->field($model, 'laser_type') ?>

    <?php // echo $form->field($model, 'laser_count') ?>

    <?php // echo $form->field($model, 'laser1_power') ?>

    <?php // echo $form->field($model, 'laser1_d') ?>

    <?php // echo $form->field($model, 'laser1_wl') ?>

    <?php // echo $form->field($model, 'laser2_power') ?>

    <?php // echo $form->field($model, 'laser2_d') ?>

    <?php // echo $form->field($model, 'laser2_wl') ?>

    <?php // echo $form->field($model, 'layer_thickness_min') ?>

    <?php // echo $form->field($model, 'layer_thickness_max') ?>

    <?php // echo $form->field($model, 'scan_speed_max') ?>

    <?php // echo $form->field($model, 'dimension_l') ?>

    <?php // echo $form->field($model, 'dimension_w') ?>

    <?php // echo $form->field($model, 'dimension_h') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'dimension_inst_l') ?>

    <?php // echo $form->field($model, 'dimension_inst_w') ?>

    <?php // echo $form->field($model, 'dimension_inst_h') ?>

    <?php // echo $form->field($model, 'dimension_tran_l') ?>

    <?php // echo $form->field($model, 'dimension_tran_w') ?>

    <?php // echo $form->field($model, 'dimension_tran_h') ?>

    <?php // echo $form->field($model, 'energy_supply') ?>

    <?php // echo $form->field($model, 'gas_type') ?>

    <?php // echo $form->field($model, 'gas_consumption') ?>

    <?php // echo $form->field($model, 'gas_pressure') ?>

    <?php // echo $form->field($model, 'cnc_system') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
