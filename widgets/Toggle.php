<?php

namespace app\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class Toggle extends \yii\base\Widget
{
    public $model;
    public $attribute;
    public $enableLabel = true;
    public $form;
    public $options;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $id = strtolower($this->model->formName()) . '-' . $this->attribute;
        $state = ArrayHelper::getValue($this->model, $this->attribute);

        $content[] = Html::beginTag('div', ['class' => 'field-' . $id]);
        $content[] = Html::beginTag('div', ['class' => 'toggle']);
        $content[] = Html::checkbox(Html::getInputName($this->model, $this->attribute), $state, ['id' => $id, 'uncheck' => '0', 'form' => $this->form]);
        $content[] = Html::label('', $id, $this->options);
        if ($this->enableLabel) $content[] = Html::tag('span', $this->model->getAttributeLabel($this->attribute));
        $content[] = Html::endTag('div');
        $content[] = Html::endTag('div');

        return implode(PHP_EOL, $content) . PHP_EOL;
    }
}
