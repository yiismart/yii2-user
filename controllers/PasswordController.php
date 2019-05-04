<?php

namespace smart\user\backend\controllers;

use Yii;
use yii\web\Controller;
use smart\user\backend\forms\PasswordChangeForm;

class PasswordController extends Controller
{

    /**
     * Change password
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->getUser();

        // Check login
        if ($user->getIsGuest()) {
            return $user->loginRequired();
        }

        $object = $user->getIdentity();
        $model = new PasswordChangeForm;
        $model->assignFrom($object);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            if ($object->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'The new password has been set.'));
            }
            return $this->goBack();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

}