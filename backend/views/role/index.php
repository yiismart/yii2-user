<?php

use yii\helpers\Html;
use yii\helpers\Url;
use smart\grid\GridView;

// Title
$title = Yii::t('user', 'Roles');
$this->title = $title . ' | ' . Yii::$app->name;

// Breadcrumbs
$this->params['breadcrumbs'] = [
    Yii::t('user', 'Security'),
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<p>
    <?= Html::a(Yii::t('cms', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => $model->getDataProvider(),
    'columns' => [
        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function ($model, $key, $index, $column) {
                $r = Html::encode($model->name);
                if (!empty($model->description)) {
                    $r .= ' ' . Html::tag('span', Html::encode('(' . $model->description . ')'), ['class' => 'text-muted']);
                }

                // $children = Yii::$app->authManager->getChildren($model->name);
                foreach ($model->children as $item) {
                    $r .= ' ' . Html::tag('span', Html::encode($item->name), [
                        // 'class' => $item->type == $child::TYPE_ROLE ? 'label label-primary' : 'label label-default',
                        'class' => $item instanceof Role ? 'label label-primary' : 'label label-secondary',
                        'title' => $item->description,
                    ]);
                }
                return $r;
            },
        ],
        [
            'class' => 'smart\grid\ActionColumn',
            'urlCreator' => function ($action, $model, $key, $index) {
                return Url::toRoute([$action, 'name' => $model->name]);
            },
        ],
    ],
]) ?>
