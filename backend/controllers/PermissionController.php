<?php

namespace smart\user\backend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use smart\base\BackendController;
use smart\user\backend\filters\PermissionFilter;
use smart\user\backend\forms\PermissionForm;
use smart\user\backend\models\Permission;

class PermissionController extends BackendController
{

    /**
     * List
     * @return string
     */
    public function actionIndex()
    {
        $model = new PermissionFilter;
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
        $object = new Permission;
        $model = new PermissionForm;

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
     * @param string $name
     * @return string
     */
    public function actionUpdate($name)
    {
        $object = Permission::find($name);
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        $model = new PermissionForm;
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
     * Delete
     * @param string $name
     * @return string
     */
    public function actionDelete($name)
    {
        $object = Permission::find($name);
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        if ($object->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('cms', 'Item deleted successfully.'));
        }

        return $this->redirect(['index']);
    }

}
