<?php

namespace smart\user\backend\assets;

use yii\web\AssetBundle;

class RoleAsset extends AssetBundle
{

    public $js = [
        'role.js',
    ];
    
    public $css = [
        'role.css',
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();
        $this->sourcePath = __DIR__ . '/role';
    }

}
