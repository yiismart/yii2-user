<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'own')->checkbox() ?>

    <div class="form-group form-buttons row">
        <div class="col-sm-12">
            <?= Html::submitButton(Yii::t('cms', 'Save'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('cms', 'Cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
