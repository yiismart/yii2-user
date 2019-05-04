<?php

namespace smart\user\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use smart\base\BackendController;
use smart\user\filters\RoleFilter;
use smart\user\forms\RoleForm;
use smart\user\models\Role;
use smart\user\models\User;

class RoleController extends BackendController
{
    /**
     * List
     * @return string
     */
    public function actionIndex()
    {
        $model = new RoleFilter;
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
        $object = new Role;
        $model = new RoleForm;

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
     * @return void
     */
    public function actionUpdate($name)
    {
        $object = Role::findOne($name);
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        $model = new RoleForm;
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
     * Role deleting
     * @param string $name Role name
     * @return void
     */
    public function actionDelete($name)
    {
        $object = Role::findOne($name);
        if ($object === null) {
            throw new BadRequestHttpException(Yii::t('cms', 'Item not found.'));
        }

        if ($object->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('cms', 'Item deleted successfully.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * User autocomplete
     * @param string $term 
     * @return string
     */
    public function actionUsers($term)
    {
        $users = User::find()->andWhere(['and',
            ['<>', 'email', 'admin'],
            ['like', 'email', $term],
        ])->limit(8)->all();

        return Json::encode(array_map(function ($user) {
            return [
                'id' => $user->id,
                'value' => $user->email,
            ];
        }, $users));
    }
}
