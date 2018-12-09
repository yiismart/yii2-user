<?php

namespace smart\user\backend\controllers;

use Yii;
use smart\base\BackendController;
use smart\user\backend\filters\RoleFilter;
use smart\user\backend\forms\RoleForm;
use smart\user\backend\models\Role;

// use yii\data\ArrayDataProvider;
// use yii\filters\AccessControl;
// use yii\helpers\Json;
// use yii\web\BadRequestHttpException;

// use cms\user\backend\models\RoleForm;
// use cms\user\common\models\User;

class RoleController extends BackendController
{

    // /**
    //  * Predefined actions
    //  * @return array
    //  */
    // public function actions()
    // {
    //     return [
    //         'users' => 'cms\user\common\actions\AutoComplete',
    //     ];
    // }

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

    // /**
    //  * Role editting
    //  * @param string $name Role name
    //  * @return void
    //  */
    // public function actionUpdate($name)
    // {
    //     $item = Yii::$app->authManager->getRole($name);
    //     if ($item === null) throw new BadRequestHttpException(Yii::t('user', 'Role not found.'));

    //     $model = new RoleForm(['item' => $item]);
    //     if ($model->load(Yii::$app->request->post()) && $model->update()) {
    //         Yii::$app->session->setFlash('success', Yii::t('user', 'Changes saved successfully.'));
    //         return $this->redirect(['index']);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    // /**
    //  * Role deleting
    //  * @param string $name Role name
    //  * @return void
    //  */
    // public function actionDelete($name)
    // {
    //     $auth = Yii::$app->authManager;

    //     $item = $auth->getRole($name);
    //     if ($item === null) throw new BadRequestHttpException(Yii::t('user', 'Role not found.'));

    //     if ($auth->remove($item)) Yii::$app->session->setFlash('success', Yii::t('user', 'Role deleted successfully.'));

    //     return $this->redirect(['index']);
    // }

    // /**
    //  * User role assignment
    //  * @param string $email User email
    //  * @return void
    //  */
    // public function actionAssign($email)
    // {
    //     //user
    //     $user = User::findByEmail($email);
    //     if ($user === null) return Json::encode([
    //         'error' => Yii::t('user', 'User not found.'),
    //     ]);

    //     $model = new RoleForm(['users' => [$user->id]]);
    //     return Json::encode([
    //         'content' => $this->renderPartial('form/assignment', ['model' => $model]),
    //     ]);
    // }

}
