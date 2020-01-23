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

$maxImageSize = $model::MAX_IMAGE_SIZE * 1048576;

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

$('#machine-image').change(function() {
    var blob = this.files[0];

    if (blob && (blob.size < $maxImageSize) && (blob.type == 'image/jpeg' || blob.type == 'image/png')) {
        $('#preview-image').attr('src', URL.createObjectURL(blob));
        $('#upload-image').removeAttr('disabled');
    } else {
        $('#upload-image').prop('disabled', true);
        $('#preview-image').attr('src', '$blankImage');
    }
});

var deleteGalleryItem = function() {
    $(this).prop('disabled', true);
    $(this).parent().remove();
};

var createGalleryItem = function(r) {
    $('<div/>', { class: 'item' }).appendTo('.section-gallery .wrap').append(
        $('<a/>', { href: r.imageUrl }).append(
            $('<img>', { src: r.thumbUrl, title: r.title })
        ).magnificPopup({ type: 'image', closeOnContentClick: true, tClose: '$tClose', tLoading: '$tLoading', image: { cursor: false, tError: '$tError' }}),
        $('<button/>', { type: 'button', class: 'delete-button', text: '$tDelete', click: deleteGalleryItem }),
        $('<input>', { type: 'hidden', name: 'Machine[raw_images][]', value: r.value })
    );

    URL.revokeObjectURL($('#preview-image').attr('src'));
};

$('#upload-image').click(function() {
    var blob = $('#machine-image').prop('files')[0];
    if (!blob) return;

    $(this).prop('disabled', true).addClass('spin');

    var data = new FormData();
    data.append('Machine[image]', blob);

    $.ajax({
        type: 'POST',
        url: 'upload-image',
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: createGalleryItem,
        complete: function() { setTimeout(() => { $('#upload-image').removeClass('spin'); }, 200); }
    });
});

$('.section-gallery .item a').magnificPopup({ type: 'image', closeOnContentClick: true, tClose: '$tClose', tLoading: '$tLoading', image: { cursor: false, tError: '$tError' }});

$('.section-gallery .item button').click(deleteGalleryItem);
JS;

$this->registerJs($js, yii\web\View::POS_READY);

$this->params['breadcrumbs'][] = ['label' => '<span class="spacer"></span>'];
$this->params['breadcrumbs'][] = ['label' => '<span>' . $model->getAttributeLabel('draft') . '</span>'];
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
                <div class="col-12 field">
                    <?= $form->field($model, 'name')->textInput(['class' => 'form-control form-control-lg', 'autofocus' => true, 'maxlength' => true]) ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'rev')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'process')->dropDownList($model->select->process) ?>
                </div>

                <div class="col-12 field">
                    <?= $form->field($model, 'manufacturer')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 field">
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
                <div class="col-6 field">
                    <?= $form->field($model, 'build_x') ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'build_y') ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'build_z') ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'build_d') ?>
                </div>

                <div class="col-12">
                    <?= Toggle::widget([
                        'model' => $model,
                        'attribute' => 'build_heat',
                        'options' => [
                            'data-toggle' => 'collapse',
                            'data-target' => '.row-build-heat',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="row row-build-heat collapse<?= $model->build_heat ? ' show' : '' ?>">
                <div class="col-6 field">
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
                <div class="col-12 field">
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
                $content[] = Html::tag('div', $form->field($model, 'laser' . $i . '_power'), ['class' => 'col-6 field']);
                $content[] = Html::tag('div', $form->field($model, 'laser' . $i . '_d'), ['class' => 'col-6 field']);
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
                <div class="col-6 field">
                    <?= $form->field($model, 'layer_min') ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'layer_max') ?>
                </div>

                <div class="col-12 field">
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
                <div class="col-6 field">
                    <?= $form->field($model, 'dim_base_l') ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'dim_base_w') ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'dim_base_h') ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'weight') ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Габариты установленного оборудования') ?></h5>
            <div class="row">
                <div class="col-6 field">
                    <?= $form->field($model, 'dim_inst_l') ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'dim_inst_w') ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'dim_inst_h') ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Транспортные габариты') ?></h5>
            <div class="row">
                <div class="col-6 field">
                    <?= $form->field($model, 'dim_tran_l') ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'dim_tran_w') ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'dim_tran_h') ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h5><?= Yii::t('app', 'Электроподключение') ?></h5>
            <div class="row">
                <div class="col-12 field">
                    <?= $form->field($model, 'mains_connection')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 field">
                    <?= $form->field($model, 'voltage') ?>
                </div>

                <div class="col-12 field">
                    <?= $form->field($model, 'frequency') ?>
                </div>

                <div class="col-12 field">
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
                <div class="col-6 field">
                    <?= $form->field($model, 'raw_gas_type')->checkboxList($model->select->gas_type, ['item' => function($index, $label, $name, $checked, $value) {
                        $id = 'gas-type-' . ($index + 1);

                        $content[] = Html::beginTag('div', ['class' => 'checkbox']);
                        $content[] = Html::checkbox($name, $checked, ['id' => $id, 'value' => $value]);
                        $content[] = Html::label($label, $id);
                        $content[] = Html::endTag('div');

                        return implode(PHP_EOL, $content) . PHP_EOL;
                    }]) ?>
                </div>

                <div class="col-6 field">
                    <?= $form->field($model, 'gas_purity')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 field">
                    <?= $form->field($model, 'gas_cons_min') ?>
                </div>

                <div class="col-12 field">
                    <?= $form->field($model, 'gas_pres_min') ?>
                </div>

                <div class="col-12 field">
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
        <div class="section section-upload-image">
            <h5><?= Yii::t('app', 'Загрузить изображение') ?></h5>
            <div class="wrap">
                <?= Html::img($blankImage, ['id' => 'preview-image', 'alt' => false]) ?>

                <div class="field-machine-image">
                    <div class="file-input">
                        <?= $form->field($model, 'image', [
                            'template' => "{hint}\n{input}\n{label}\n<button type=\"button\" id=\"upload-image\" class=\"field-button\" disabled>" . Yii::t('app', 'Загрузить') . "</button>\n{error}",
                            'labelOptions' => ['class' => 'field-button', 'role' => 'button'],
                            'options' => ['tag' => false],
                            'enableError' => true,
                        ])->fileInput() ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="section section-gallery">
            <h5><?= Yii::t('app', 'Галерея') ?></h5>
            <input type="hidden" name="Machine[raw_images]" value="">
            <div class="wrap" data-content="<?= Yii::t('app', 'Нет добавленных элементов') ?>"><?php
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
