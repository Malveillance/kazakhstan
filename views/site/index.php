<?php

/* @var $this yii\web\View */

$this->title = Yii::t('yii', 'Home');

$this->params['breadcrumbs'][] = ['label' => '<span>' . $this->title . '</span>'];
?>
<p><?= 'PHP Version: ' . phpversion() ?></p>
<p><?= 'Apache Version: ' . apache_get_version() ?></p>
<p><?= 'Default Timezone: ' . date_default_timezone_get() ?></p>
<p><?= 'Maximum Upload Size: ' . ini_get('upload_max_filesize') ?></p>
