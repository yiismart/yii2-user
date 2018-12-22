<?php

use yii\helpers\Html;
use yii\helpers\Url;
use smart\grid\GridView;

// Title
$title = Yii::t('user', 'Permissions');
$this->title = $title . ' | ' . Yii::$app->name;

// Breadcrumbs
$this->params['breadcrumbs'] = [
    Yii::t('user', 'Security'),
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<p class="form-buttons">
    <?= Html::a(Yii::t('cms', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => $model->getDataProvider(),
    'columns' => [
        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function($model, $key, $index, $column) {
                $r = Html::encode($model->name);
                if (!empty($model->description)) {
                    $r .= ' ' . Html::tag('span', Html::encode('('.$model->description.')'), ['class' => 'text-muted']);
                }
                if ($model->own) {
                    $r .= ' ' . Html::tag('span', Html::encode($model->getAttributeLabel('own')), ['class' => 'badge badge-primary']);
                }

                return $r;
            },
        ],
        [
            'class' => 'smart\grid\ActionColumn',
            'urlCreator' => function($action, $model, $key, $index) {
                return Url::toRoute([$action, 'name' => $model->name]);
            },
        ],
    ],
]) ?>
