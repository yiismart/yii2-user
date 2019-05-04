<?php

use smart\widgets\ActiveForm;
use yii\helpers\Html;

// Title
$title = Yii::t('user', 'Login');
$this->title = $title . ' | ' . Yii::$app->name;

?>
<h1><?= Html::encode($title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>
    
    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group form-buttons row">
        <div class="col-sm-10 offset-sm-2">
            <?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
