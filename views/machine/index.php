<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yii\db\ActiveQuery */

$this->title = Yii::t('app', 'Список оборудования');

$letter = '';

$this->params['breadcrumbs'][] = ['label' => '<span>' . $this->title . '</span>'];

if (!Yii::$app->user->isGuest) {
    $this->params['breadcrumbs'][] = ['label' => '<span class="spacer"></span>'];
    $this->params['breadcrumbs'][] = ['label' => Html::a(Yii::t('app', 'Добавить'), ['add'], ['role' => 'button', 'class' => 'bread-button'])];
}
?>
<ul class="machine-list">
    <?php foreach ($model as $item) {
        $current = mb_substr($item->name, 0, 1);
        if ($current != $letter) $content[] = '<li class="initial">' . ($letter = $current) . '</li>';

        $content[] = '<li>' . Html::a(Html::encode($item->name), ['machine/view', 'id' => $item->id]) . '</li>';

        echo implode(PHP_EOL, $content) . PHP_EOL;
        unset($content);
    } ?>
</ul>
