<?php

namespace smart\user\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use smart\base\BackendController;
use smart\user\filters\UserFilter;
use smart\user\forms\UserForm;
use smart\user\forms\UserPasswordForm;
use smart\user\models\User;

class UserController extends BackendController
{
    /**
     * List
     * @return string
     */
    public function actionIndex()
    {
        $model = new UserFilter;
        $model->load(Yii::$app->getRequest()->get());

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Create
     * @return string
     */
    public function actionCreate()
    {
        $object = new User;
        $model = new UserForm(['scenario' => 'create']);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            if ($object->save()) {
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Changes saved successfully.'));
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Update
     * @param integer $id 
     * @return string
     */
    public function actionUpdate($id)
    {
        $object = User::findOne($id);
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        $model = new UserForm;
        $model->assignFrom($object);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            if ($object->save()) {
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Changes saved successfully.'));
            }
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'object' => $object,
        ]);
    }

    /**
     * Set password
     * @param integer $id 
     * @return string
     */
    public function actionPassword($id)
    {
        $object = User::findOne($id);
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        $model = new UserPasswordForm;
        $model->assignFrom($object);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->assignTo($object);
            if ($object->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('user', 'The new password has been set.'));
            }
            return $this->redirect(['index']);
        }

        return $this->render('password', [
            'model' => $model,
            'object' => $object,
        ]);
    }
}
