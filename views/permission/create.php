<?php

use yii\helpers\Html;

// Title
$title = Yii::t('user', 'Add new permission');
$this->title = $title . ' | ' . Yii::$app->name;

// Breadcrumbs
$this->params['breadcrumbs'] = [
    Yii::t('user', 'Security'),
    ['label' => Yii::t('user', 'Permissions'), 'url' => ['index']],
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', [
    'model' => $model,
]) ?>
