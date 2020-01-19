<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use app\widgets\Toggle;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\Machine */

app\assets\MagnificPopupAsset::register($this);

$tDelete = Yii::t('app', 'Удалить');
$tClose = Yii::t('app', 'Закрыть (Esc)');
$tLoading = Yii::t('app', 'Загрузка...');
$tError = Yii::t('app', 'Ошибка при загрузке изображения.');

$maxImageSize = app\models\Machine::MAX_IMAGE_SIZE * 1048576;

$blankImage = Url::toRoute('images/site/blank.png', true);

$js = <<< JS
$('#laser-count').on('input', function() {
    $(this).siblings('output').text($(this).val());
});

$('#laser-count').change(function() {
    for (var i = 2; i < 5; i++) {
        if (i <= $(this).val()) $('#laser-' + i).slideDown(250);
        else $('#laser-' + i).slideUp(250);
    }
});

$('#machine-upload').change(function() {
    var blob = this.files[0];

    if (blob && (blob.size < $maxImageSize) && (blob.type == 'image/jpeg' || blob.type == 'image/png')) {
        $('#upload-image img').attr('src', URL.createObjectURL(blob));
        $('#upload-button').removeAttr('disabled');
    } else {
        $('#upload-button').prop('disabled', true);
        $('#upload-image img').attr('src', '$blankImage');
    }
});

var deleteGalleryItem = function() {
    $(this).prop('disabled', true);
    $(this).parent().remove();
};

var createGalleryItem = function(r) {
    $('<div/>', { class: 'item' }).appendTo('#gallery').append(
        $('<a/>', { href: r.imageUrl }).append(
            $('<img>', { src: r.thumbUrl, title: r.title })
        ).magnificPopup({ type: 'image', closeOnContentClick: true, tClose: '$tClose', tLoading: '$tLoading', image: { cursor: false, tError: '$tError' }}),
        $('<button/>', { type: 'button', class: 'delete-button', text: '$tDelete', click: deleteGalleryItem }),
        $('<input>', { type: 'hidden', name: 'Machine[raw_images][]', value: r.value })
    );

    URL.revokeObjectURL($('#upload-image img').attr('src'));
};

$('#upload-button').click(function() {
    var blob = $('#machine-upload').prop('files')[0];
    if (!blob) return;

    $(this).prop('disabled', true).addClass('spin');

    var data = new FormData();
    data.append('Machine[upload]', blob);

    $.ajax({
        type: 'POST',
        url: 'upload-image',
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: createGalleryItem,
        complete: function() { setTimeout(() => { $('#upload-button').removeClass('spin'); }, 200); }
    });
});

$('#gallery a').magnificPopup({ type: 'image', closeOnContentClick: true, tClose: '$tClose', tLoading: '$tLoading', image: { cursor: false, tError: '$tError' }});

$('#gallery button').click(deleteGalleryItem);
JS;

$this->registerJs($js, yii\web\View::POS_READY);

$this->params['breadcrumbs'][] = ['label' => '<span class="ml-auto">' . $model->getAttributeLabel('draft') . '</span>'];
$this->params['breadcrumbs'][] = ['label' => Toggle::widget(['model' => $model, 'attribute' => 'draft', 'enableLabel' => false, 'form' => 'form-machine'])];
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-machine',
    'successCssClass' => 'valid',
    'errorCssClass' => 'invalid',
    'fieldConfig' => [
        'template' => "{hint}\n{input}\n{label}\n{error}",
        'labelOptions' => ['class' => 'form-text'],
        'errorOptions' => ['class' => 'form-feedback'],
        'hintOptions' => ['class' => 'form-hint'],
        'options' => ['class' => null],
        'enableError' => false,
    ],
]) ?>

<div class="row">
    <div class="col-12 col-sm-6 pane">
        <div class="section">
            <h5><?= Yii::t('app', 'Общая информация') ?></h5>
            <div class="row">
                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'name')->textInput(['class' => 'form-control form-control-lg', 'autofocus' => true, 'maxlength' => true]) ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'rev')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'process')->dropDownList($model->select->process) ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'manufacturer')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'manufacturer_url')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12">
                    <?= $form->field($model, 'manufacturer_country')->dropDownList($model->select->countries) ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Параметры зоны построения') ?></h5>
            <div class="row">
                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'build_platform_x') ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'build_platform_y') ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'build_platform_z') ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'build_platform_d') ?>
                </div>

                <div class="col-12">
                    <?= Toggle::widget([
                        'model' => $model,
                        'attribute' => 'build_heat',
                        'options' => [
                            'data-toggle' => 'collapse',
                            'data-target' => '#build-heat-collapse',
                            'aria-expanded' => $model->build_heat ? 'true' : 'false',
                            'aria-controls' => 'build-heat-collapse',
                        ],
                    ]) ?>
                </div>
            </div>

            <div id="build-heat-collapse" class="row collapse<?= $model->build_heat ? ' show' : '' ?>">
                <div class="col-6 wrap-field wrap-collapse">
                    <?= $form->field($model, 'build_heat_t_max') ?>
                </div>

                <div class="col-12">
                    <?= $form->field($model, 'build_heat_desc')->textarea(['rows' => '5']) ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Характеристики лазера') ?></h5>
            <div class="row">
                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'laser_type')->dropDownList($model->select->laser_type) ?>
                </div>

                <div class="col-12">
                    <div class="field-machines-laser_count">
                        <div class="slider">
                            <input type="range" id="laser-count" name="<?= Html::getInputName($model, 'laser_count') ?>" min="1" max="4" step="1" value="<?= $model->laser_count ?>">
                            <output class="form-control" for="laser-count"><?= $model->laser_count ?></output>
                            <label class="form-text" for="laser-count"><?= $model->getAttributeLabel('laser_count') ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <?php for ($i = 1; $i < 5; $i++) {
                $content[] = Html::beginTag('div', ['id' => 'laser-' . $i, 'style' => $model->laser_count < $i ? 'display: none;' : '']);
                $content[] = Html::tag('p', Yii::t('app', 'Лазер') . ' ' . $i, ['class' => 'h7']);
                $content[] = Html::beginTag('div', ['class' => 'row']);
                $content[] = Html::tag('div', $form->field($model, 'laser' . $i . '_power'), ['class' => 'col-6 wrap-field']);
                $content[] = Html::tag('div', $form->field($model, 'laser' . $i . '_d'), ['class' => 'col-6 wrap-field']);
                $content[] = Html::tag('div', $form->field($model, 'laser' . $i . '_wl')->textInput(['placeholder' => '0.00']), ['class' => 'col-6']);
                $content[] = Html::endTag('div');
                $content[] = Html::endTag('div');

                echo implode(PHP_EOL, $content) . PHP_EOL;
                unset($content);
            } ?>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Производительность процесса') ?></h5>
            <div class="row">
                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'layer_thickness_min') ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'layer_thickness_max') ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'scan_speed_max') ?>
                </div>

                <div class="col-12">
                    <?= $form->field($model, 'performance')->textInput(['placeholder' => '0.00']) ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Габариты оборудования') ?></h5>
            <div class="row">
                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'dimension_l') ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'dimension_w') ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'dimension_h') ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'weight') ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Габариты установленного оборудования') ?></h5>
            <div class="row">
                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'dimension_inst_l') ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'dimension_inst_w') ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'dimension_inst_h') ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Транспортные габариты') ?></h5>
            <div class="row">
                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'dimension_tran_l') ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'dimension_tran_w') ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'dimension_tran_h') ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Электроподключение') ?></h5>
            <div class="row">
                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'mains_connection')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'voltage') ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'frequency') ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'power_cons') ?>
                </div>

                <div class="col-12">
                    <?= $form->field($model, 'mains_fuse') ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Подключение защитного газа') ?></h5>
            <div class="row">
                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'raw_gas_type')->checkboxList($model->select->gas_type, ['item' => function($index, $label, $name, $checked, $value) {
                        $id = 'gas-type-' . ($index + 1);

                        $content[] = Html::beginTag('div', ['class' => 'checkbox']);
                        $content[] = Html::checkbox($name, $checked, ['id' => $id, 'value' => $value]);
                        $content[] = Html::label($label, $id);
                        $content[] = Html::endTag('div');

                        return implode(PHP_EOL, $content) . PHP_EOL;
                    }]) ?>
                </div>

                <div class="col-6 wrap-field">
                    <?= $form->field($model, 'gas_purity')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'gas_cons_min') ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'gas_pressure_min') ?>
                </div>

                <div class="col-12 wrap-field">
                    <?= $form->field($model, 'gas_cons_purge') ?>
                </div>

                <div class="col-12">
                    <?= $form->field($model, 'gas_cons_build') ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Сетевое подключение') ?></h5>
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'connection_type')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Управление') ?></h5>
            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'cnc_system')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 pane">
        <div class="section">
            <h5><?= Yii::t('app', 'Загрузить изображение') ?></h5>
            <div id="upload-image">
                <?= Html::img($blankImage, ['class' => 'mx-auto']) ?>

                <div class="field-machine-upload wrap-upload">
                    <div class="file-input">
                        <?= $form->field($model, 'upload', [
                            'template' => "{hint}\n{input}\n{label}\n<button type=\"button\" id=\"upload-button\" class=\"field-button\" disabled>" . Yii::t('app', 'Загрузить') . "</button>\n{error}",
                            'labelOptions' => ['class' => 'field-button', 'role' => 'button'],
                            'options' => ['tag' => false],
                            'enableError' => true,
                        ])->fileInput() ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Галерея') ?></h5>
            <input type="hidden" name="Machine[raw_images]" value="">
            <div id="gallery" data-content="<?= Yii::t('app', 'Нет добавленных элементов') ?>"><?php
                if (!empty($model->raw_images)) foreach ($model->raw_images as $filename) {
                    $path = Yii::$app->params['uploadsPath'] . $filename;
                    $thumbpath = Yii::$app->params['thumbsPath'] . $filename;

                    $content[] = Html::beginTag('div', ['class' => 'item']);
                    $content[] = Html::beginTag('a', ['href' => Url::toRoute($path, true)]);
                    $content[] = Html::img(Url::toRoute($thumbpath, true), ['title' => $model->getImageInfo($path), 'alt' => false]);
                    $content[] = Html::endTag('a');
                    $content[] = Html::button($tDelete, ['class' => 'delete-button']);
                    $content[] = Html::hiddenInput('Machine[raw_images][]', base64_encode($filename));
                    $content[] = Html::endTag('div');

                    echo implode(PHP_EOL, $content) . PHP_EOL;
                    unset($content);
                } ?></div>
        </div>

        <div class="section section-submit spacer">
            <div class="sticky">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'form-button']) ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>
