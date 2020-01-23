<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = Yii::t('yii', $name);

unset($this->params['breadcrumbs']);

if (Yii::$app->errorHandler->exception->statusCode) {
    $this->params['breadcrumbs'][] = ['label' => '<span class="d-none d-sm-block">' . Yii::t('yii', 'Error') . '<i class="fa fa-chevron-right"></i></span>'];
    $this->params['breadcrumbs'][] = ['label' => '<span class="id">#' . Yii::$app->errorHandler->exception->statusCode . '</span>'];
} else {
    $this->params['breadcrumbs'][] = ['label' => '<span>' . Yii::t('yii', 'Error') . '</span>'];
}
?>
<h2><?= Html::encode($this->title) ?></h2>

<div class="alert alert-danger">
    <?= Html::encode($message) ?>
</div>
