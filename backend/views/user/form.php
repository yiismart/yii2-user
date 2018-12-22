<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

// Roles
$roles = $model->getAvailableRoles();

?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
]); ?>

    <?= $form->field($model, 'admin')->checkbox() ?>
    <?= $form->field($model, 'active')->checkbox() ?>
    <?= $form->field($model, 'email')->textInput($model->scenario == 'create' ? [] : ['disabled' => true]) ?>
    <?= $form->field($model, 'firstName') ?>
    <?= $form->field($model, 'lastName') ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'roles')->checkboxList($roles) ?>

    <div class="form-group form-buttons row">
        <div class="col-sm-12">
            <?= Html::submitButton(Yii::t('cms', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('cms', 'Cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
