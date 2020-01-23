<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Machine */

app\assets\MagnificPopupAsset::register($this);

$this->title = $model->name;

$tDelete = Yii::t('app', 'Удалить');
$tConfirm = Yii::t('app', 'Вы уверены?');
$tClose = Yii::t('app', 'Закрыть (Esc)');
$tLoading = Yii::t('app', 'Загрузка...');
$tError = Yii::t('app', 'Ошибка при загрузке изображения.');

$build_dz = $model->build_d && $model->build_z;
$build_xyz = $model->build_x && $model->build_y && $model->build_z;

$dim_base_lwh = $model->dim_base_l && $model->dim_base_w && $model->dim_base_h;
$dim_inst_lwh = $model->dim_inst_l && $model->dim_inst_w && $model->dim_inst_h;
$dim_tran_lwh = $model->dim_tran_l && $model->dim_tran_w && $model->dim_tran_h;

$js = <<< JS
$('#delete-button').click(function(e) { e.preventDefault(); if ($(this).hasClass('confirm')) $.post($(this).attr('href')); $(this).text('$tConfirm').addClass('confirm'); });
$('#delete-button').focusout(function() { $(this).text('$tDelete').removeClass('confirm'); });

$('.section-gallery .main a').magnificPopup({ type: 'image', closeOnContentClick: true, tClose: '$tClose', tLoading: '$tLoading', image: { cursor: false, tError: '$tError' }});
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
<div class="row">
    <div class="col-12 col-fixed pane">
        <div class="section section-gallery">
            <?php if (!empty($model->raw_images)): ?>
                <div class="main">
                    <?php $path = Url::toRoute(Yii::$app->params['uploadsPath'] . $model->raw_images[0], true) ?>
                    <a href="<?= $path ?>"><img src="<?= $path ?>"></a>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="col-12 col-sm pane">
        <div class="section">
            <h2 class="mb-0"><?= Html::encode($model->name) ?></h2>
            <?php if ($model->rev): ?>
                <span class="rev"><?= $model->getAttributeLabel('rev') . ' ' . Html::encode($model->rev) ?></span>
            <?php endif ?>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Общая информация') ?></h5>
            <dl class="data">
                <dt><span><?= $model->getAttributeLabel('process') ?></span></dt>
                <dd><span><?= $model->select->process[$model->process] ?></span></dd>
            </dl>

            <?php if ($model->manufacturer): ?>
                <dl class="data">
                    <dt><span><?= $model->getAttributeLabel('manufacturer') ?></span></dt>
                    <dd><span><?= Html::encode($model->manufacturer) . ($model->manufacturer_country ? (' (' . $model->select->countries[$model->manufacturer_country] . ')') : '') ?></span></dd>
                </dl>
            <?php endif ?>

            <?php if ($model->manufacturer_url): ?>
                <dl class="data">
                    <dt><span><?= $model->getAttributeLabel('manufacturer_url') ?></span></dt>
                    <dd><span><a href="<?= $model->manufacturer_url ?>" target="_blank"><?= parse_url($model->manufacturer_url, PHP_URL_HOST) ?></a></span></dd>
                </dl>
            <?php endif ?>
        </div>

        <?php if ($build_dz || $build_xyz || $model->build_heat): ?>
            <div class="section">
                <h5><?= Yii::t('app', 'Параметры зоны построения') ?></h5>
                <?php if ($build_dz): ?>
                    <dl class="data">
                        <dt><span><?= Yii::t('app', 'Размеры зоны построения') ?></span></dt>
                        <dd><span><?= $model->build_d . 'x' . $model->build_z . ' ' . $model->getAttributeHint('build_z') . ' (Ø)' ?></span></dd>
                    </dl>
                <?php elseif ($build_xyz): ?>
                    <dl class="data">
                        <dt><span><?= Yii::t('app', 'Размеры зоны построения') ?></span></dt>
                        <dd><span><?= $model->build_x . 'x' . $model->build_y . 'x' . $model->build_z . ' ' . $model->getAttributeHint('build_z') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->build_heat): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('build_heat') ?></span></dt>
                        <dd><span><?= Yii::t('app', 'Есть') ?></span></dd>
                    </dl>

                    <?php if ($model->build_heat_t_max): ?>
                        <dl class="data">
                            <dt><span><?= $model->getAttributeLabel('build_heat_t_max') ?></span></dt>
                            <dd><span><?= $model->build_heat_t_max . ' ' . $model->getAttributeHint('build_heat_t_max') ?></span></dd>
                        </dl>
                    <?php endif ?>

                    <?php if ($model->build_heat_desc): ?>
                        <dl class="data">
                            <dt><span><?= $model->getAttributeLabel('build_heat_desc') ?></span></dt>
                            <dd><span><?= Html::encode($model->build_heat_desc) ?></span></dd>
                        </dl>
                    <?php endif ?>
                <?php endif ?>
            </div>
        <?php endif ?>

        <div class="section">
            <h5><?= Yii::t('app', 'Характеристики лазера') ?></h5>
            <dl class="data">
                <dt><span><?= $model->getAttributeLabel('laser_type') ?></span></dt>
                <dd><span><?= $model->select->laser_type[$model->laser_type] ?></span></dd>
            </dl>

            <dl class="data">
                <dt><span><?= $model->getAttributeLabel('laser_count') ?></span></dt>
                <dd><span><?= $model->laser_count ?></span></dd>
            </dl>

            <?php for ($i = 1; $i <= $model->laser_count; $i++): ?>
                <?php $attr = 'laser' . $i . '_power' ?>
                <?php if ($model[$attr]): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel($attr) . ' (' . $i . ')' ?></span></dt>
                        <dd><span><?= $model[$attr] . ' ' . $model->getAttributeHint($attr) ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php $attr = 'laser' . $i . '_d' ?>
                <?php if ($model[$attr]): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel($attr) . ' (' . $i . ')' ?></span></dt>
                        <dd><span><?= $model[$attr] . ' ' . $model->getAttributeHint($attr) ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php $attr = 'laser' . $i . '_wl' ?>
                <?php if ($model[$attr]): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel($attr) . ' (' . $i . ')' ?></span></dt>
                        <dd><span><?= (float)$model[$attr] . ' ' . $model->getAttributeHint($attr) ?></span></dd>
                    </dl>
                <?php endif ?>
            <?php endfor ?>
        </div>

        <?php if ($model->layer_min || $model->scan_speed_max || $model->performance): ?>
            <div class="section">
                <h5><?= Yii::t('app', 'Производительность процесса') ?></h5>
                <?php if ($model->layer_min): ?>
                    <dl class="data">
                        <dt><span><?= Yii::t('app', 'Толщина слоя') ?></span></dt>
                        <dd><span><?= $model->layer_min . ($model->layer_max ? (' – ' . $model->layer_max) : '') . ' ' . $model->getAttributeHint('layer_min') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->scan_speed_max): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('scan_speed_max') ?></span></dt>
                        <dd><span><?= $model->scan_speed_max . ' ' . $model->getAttributeHint('scan_speed_max') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->performance): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('performance') ?></span></dt>
                        <dd><span><?= (float)$model->performance . ' ' . $model->getAttributeHint('performance') ?></span></dd>
                    </dl>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if ($dim_base_lwh || $dim_inst_lwh || $dim_tran_lwh || $model->weight): ?>
            <div class="section">
                <h5><?= Yii::t('app', 'Габариты') ?></h5>
                <?php if ($dim_base_lwh): ?>
                    <dl class="data">
                        <dt><span><?= Yii::t('app', 'Габариты оборудования') ?></span></dt>
                        <dd><span><?= $model->dim_base_l . 'x' . $model->dim_base_w . 'x' . $model->dim_base_h . ' ' . $model->getAttributeHint('dim_base_l') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($dim_inst_lwh): ?>
                    <dl class="data">
                        <dt><span><?= Yii::t('app', 'Габариты установленного оборудования') ?></span></dt>
                        <dd><span><?= $model->dim_inst_l . 'x' . $model->dim_inst_w . 'x' . $model->dim_inst_h . ' ' . $model->getAttributeHint('dim_inst_l') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($dim_tran_lwh): ?>
                    <dl class="data">
                        <dt><span><?= Yii::t('app', 'Транспортные габариты') ?></span></dt>
                        <dd><span><?= $model->dim_tran_l . 'x' . $model->dim_tran_w . 'x' . $model->dim_tran_h . ' ' . $model->getAttributeHint('dim_tran_l') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->weight): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('weight') ?></span></dt>
                        <dd><span><?= $model->weight . ' ' . $model->getAttributeHint('weight') ?></span></dd>
                    </dl>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if ($model->mains_connection || $model->voltage || $model->frequency || $model->power_cons || $model->mains_fuse): ?>
            <div class="section">
                <h5><?= Yii::t('app', 'Электроподключение') ?></h5>
                <?php if ($model->mains_connection): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('mains_connection') ?></span></dt>
                        <dd><span><?= $model->mains_connection ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->voltage): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('voltage') ?></span></dt>
                        <dd><span><?= $model->voltage . ' ' . $model->getAttributeHint('voltage') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->frequency): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('frequency') ?></span></dt>
                        <dd><span><?= $model->frequency . ' ' . $model->getAttributeHint('frequency') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->power_cons): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('power_cons') ?></span></dt>
                        <dd><span><?= $model->power_cons . ' ' . $model->getAttributeHint('power_cons') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->mains_fuse): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('mains_fuse') ?></span></dt>
                        <dd><span><?= $model->mains_fuse . ' ' . $model->getAttributeHint('mains_fuse') ?></span></dd>
                    </dl>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (!empty($model->raw_gas_type) || $model->gas_purity || $model->gas_cons_min || $model->gas_pres_min || $model->gas_cons_purge || $model->gas_cons_build): ?>
            <div class="section">
                <h5><?= Yii::t('app', 'Подключение защитного газа') ?></h5>
                <?php if (!empty($model->raw_gas_type)): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('raw_gas_type') ?></span></dt>
                        <dd><span><?= implode(', ', array_intersect_key($model->select->gas_type, array_flip($model->raw_gas_type))) ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->gas_purity): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('gas_purity') ?></span></dt>
                        <dd><span><?= $model->gas_purity ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->gas_cons_min): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('gas_cons_min') ?></span></dt>
                        <dd><span><?= $model->gas_cons_min . ' ' . $model->getAttributeHint('gas_cons_min') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->gas_pres_min): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('gas_pres_min') ?></span></dt>
                        <dd><span><?= $model->gas_pres_min . ' ' . $model->getAttributeHint('gas_pres_min') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->gas_cons_purge): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('gas_cons_purge') ?></span></dt>
                        <dd><span><?= $model->gas_cons_purge . ' ' . $model->getAttributeHint('gas_cons_purge') ?></span></dd>
                    </dl>
                <?php endif ?>

                <?php if ($model->gas_cons_build): ?>
                    <dl class="data">
                        <dt><span><?= $model->getAttributeLabel('gas_cons_build') ?></span></dt>
                        <dd><span><?= $model->gas_cons_build . ' ' . $model->getAttributeHint('gas_cons_build') ?></span></dd>
                    </dl>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if ($model->connection_type): ?>
            <div class="section">
                <h5><?= Yii::t('app', 'Сетевое подключение') ?></h5>
                <dl class="data">
                    <dt><span><?= $model->getAttributeLabel('connection_type') ?></span></dt>
                    <dd><span><?= $model->connection_type ?></span></dd>
                </dl>
            </div>
        <?php endif ?>

        <?php if ($model->cnc_system): ?>
            <div class="section">
                <h5><?= Yii::t('app', 'Управление') ?></h5>
                <dl class="data">
                    <dt><span><?= $model->getAttributeLabel('cnc_system') ?></span></dt>
                    <dd><span><?= $model->cnc_system ?></span></dd>
                </dl>
            </div>
        <?php endif ?>
    </div>
</div>
