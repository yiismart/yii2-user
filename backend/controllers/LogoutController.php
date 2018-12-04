<?php

namespace smart\user\backend\controllers;

use Yii;
use yii\web\Controller;

class LogoutController extends Controller
{

    /**
     * Logout
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->getUser()->getIsGuest()) {
            return $this->goHome();
        }

        Yii::$app->getUser()->logout();

        return $this->goBack();
    }

}
