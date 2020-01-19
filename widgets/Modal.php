<?php

namespace app\widgets;

use Yii;

class Modal extends \yii\bootstrap4\Widget
{
    public $model;
    public $view;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $model = new $this->model;

        return $this->render($this->view, [
            'model' => $model,
        ]);
    }
}
