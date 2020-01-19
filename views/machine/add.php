<?php

/* @var $this yii\web\View */
/* @var $model app\models\Machine */

$this->title = Yii::t('app', 'Добавить оборудование');

$this->params['breadcrumbs'][] = ['label' => '<span>' . $this->title . '</span>'];
?>

<?= $this->render('form', [
    'model' => $model,
]) ?>
