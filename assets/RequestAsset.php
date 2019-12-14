<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class RequestAsset
 * @package app\assets
 */
class RequestAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
    ];

    public $js = [
        'js/request.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
