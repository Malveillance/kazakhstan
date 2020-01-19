<?php

/* @var $this yii\web\View */
/* @var $model app\models\Machine */

$this->title = Yii::t('app', 'Изменить оборудование');

$this->params['breadcrumbs'][] = ['label' => '<span class="d-none d-sm-block">' . $this->title . '<i class="fa fa-chevron-right"></i></span>'];
$this->params['breadcrumbs'][] = ['label' => '<span class="id">#' . $model->id . '</span>'];
?>

<?= $this->render('form', [
    'model' => $model,
]) ?>
