<?php

use yii\helpers\Html;
use smart\grid\GridView;

// Title
$title = Yii::t('user', 'Users');
$this->title = $title . ' | ' . Yii::$app->name;

// Breadcrumbs
$this->params['breadcrumbs'] = [
    Yii::t('user', 'Security'),
    $title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<p class="form-buttons">
    <?= Html::a(Yii::t('cms', 'Add new'), ['create'], ['class' => 'btn btn-primary']) ?>
</div>

<?= GridView::widget([
    'dataProvider' => $model->getDataProvider(),
    'filterModel' => $model,
    'rowOptions' => function ($model, $key, $index, $grid) {
        return !$model->active ? ['class' => 'table-warning'] : [];
    },
    'columns' => [
        [
            'attribute' => 'email',
            'format' => 'html',
            'value' => function ($model, $key, $index, $column) {
                $r = Html::encode($model->email);
                $s = $model->username;
                if ($s !== $model->email) $r .= ' ' . Html::tag('span', Html::encode('(' . $s . ')'), ['class' => 'text-muted']);
                if ($model->admin) {
                    $r .= ' ' . Html::tag('span', 'admin', ['class' => 'badge badge-danger', 'title' => Yii::t('user', 'Administrator')]);
                }
                foreach (Yii::$app->getAuthManager()->getRolesByUser($model->id) as $role) {
                    if ($role->name == 'author') {
                        continue;
                    }
                    $r .= ' ' . Html::tag('span', Html::encode($role->name), ['class' => 'badge badge-primary', 'title' => $role->description]);
                }

                return $r;
            },
        ],
        [
            'class' => 'smart\grid\ActionColumn',
            'template' => '{update} {password}',
            'buttons' => [
                'password' => function ($url, $model, $key) {
                    $title = Yii::t('user', 'Set password');

                    return Html::a('<i class="fas fa-unlock-alt"><i>', ['password', 'id' => $model->id], [
                        'title' => $title,
                        'aria-label' => $title,
                        'data-pjax' => 0,
                    ]);
                },
            ],
        ],
    ],
]) ?>
