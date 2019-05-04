<?php

namespace smart\user\backend\controllers;

use Yii;
use yii\web\Controller;
use smart\user\backend\forms\SettingsForm;

class SettingsController extends Controller
{

    /**
     * User settings
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->getUser();

        if ($user->getIsGuest()) {
            return $this->goHome();
        }

        $object = $user->getIdentity();

        $model = new SettingsForm;
        $model->assignFrom($object);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            $object->save(false);

            Yii::$app->getSession()->setFlash('success', Yii::t('cms', 'Changes saved successfully.'));
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

}
