<?php

namespace smart\user\backend\controllers;

use Yii;
use yii\web\Controller;
use smart\user\backend\forms\LoginForm;

class LoginController extends Controller
{

    /**
     * Login
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            return $this->goHome();
        }

        $model = new LoginForm;

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        };

        return $this->render('index', [
            'model' => $model,
        ]);
    }

}
