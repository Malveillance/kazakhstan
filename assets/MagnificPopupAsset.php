<?php

namespace app\assets;

use yii\web\AssetBundle;

class MagnificPopupAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/magnific-popup.css',
    ];
    public $js = [
        'js/jquery.magnific-popup.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
