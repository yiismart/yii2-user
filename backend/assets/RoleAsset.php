<?php

namespace smart\user\backend\assets;

use yii\web\AssetBundle;

class RoleAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/role';

    public $js = [
        'role.js',
    ];
    
    public $css = [
        'role.css',
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
