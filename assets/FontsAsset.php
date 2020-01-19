<?php

namespace app\assets;

use yii\web\AssetBundle;

class FontsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=cyrillic',
        'https://fonts.googleapis.com/css?family=Roboto+Condensed:400&subset=cyrillic',
        'css/font-awesome.min.css',
    ];
}
