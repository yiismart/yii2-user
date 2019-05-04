<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use smart\grid\GridView;
use smart\widgets\ActiveForm;
use smart\user\assets\RoleAsset;
use smart\user\models\User;

RoleAsset::register($this);

// Roles
$roles = $model->getAvailableRoles();

// Permissions
$permissions = $model->getAvailablePermissions();

// Users
$name = Html::getInputName($model, 'users') . '[]';
$users = User::findAll($model->users);

?>
<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?php if (!empty($roles)) echo $form->field($model, 'roles')->checkboxList($roles) ?>

    <?php if (!empty($permissions)) echo $form->field($model, 'permissions')->checkboxList($permissions) ?>

    <?= $form->field($model, 'users')->widget(AutoComplete::className(), [
        'options' => [
            'name' => '_autocomplete',
            'value' => '',
            'class' => 'form-control',
            'placeholder' => Yii::t('user', 'E-mail'),
        ],
        'clientOptions' => [
            'source' => Url::toRoute('users'),
            'appendTo' => '.field-roleform-users',
        ],
    ]) ?>
    <div class="form-group row">
        <div class="col-sm-10 offset-sm-2">
            <?= Html::hiddenInput($name, '') ?>
            <?= GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => array_merge([new User], $users),
                    'pagination' => false,
                ]),
                'id' => 'role-users',  
                'layout' => '{items}',
                'showHeader' => false,
                'columns' => [
                    ['content' => function ($model) use ($name) {
                        $id = Html::hiddenInput($name, $model->id, $model->getIsNewRecord() ? ['disabled' => true] : []);
                        $email = Html::tag('span', Html::encode($model->email));
                        return $id . $email;
                    }],
                    [
                        'class' => 'smart\grid\ActionColumn',
                        'template' => '{remove}',
                        'buttons' => [
                            'remove' => function () {
                                $title = Yii::t('yii', 'Delete');
                                return Html::a('<i class="fas fa-times"></i>', '#', [
                                    'title' => $title,
                                    'aria-label' => $title,
                                    'class' => 'remove',
                                ]);
                            },
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="form-group form-buttons row">
        <div class="col-sm-10 offset-sm-2">
            <?= Html::submitButton(Yii::t('cms', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('cms', 'Cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
