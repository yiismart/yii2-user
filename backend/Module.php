<?php

namespace smart\user\backend;

use Yii;
use yii\helpers\Html;
use smart\base\BackendModule;
use smart\user\components\AuthorRule;
use smart\user\models\User;


class Module extends BackendModule
{

    /**
     * @inheritdoc
     */
    protected static function database()
    {
        parent::database();

        if (User::find()->where(['email' => 'admin'])->count() == 0) {
            $model = new User([
                'admin' => true,
                'active' => true,
                'email' => 'admin',
                'mailing' => false,
                'passwordChange' => true,
            ]);
            $model->setPassword('admin');
            $model->save();
        }
    }

    /**
     * @inheritdoc
     */
    protected static function security()
    {
        $auth = Yii::$app->getAuthManager();
        if ($auth->getRole('author') === null) {
            //author role
            $author = $auth->createRole('author');
            $auth->add($author);
            //author rule
            $rule = new AuthorRule;
            $auth->add($rule);
            //author permission
            $own = $auth->createPermission('own');
            $own->ruleName = $rule->name;
            $auth->add($own);
            //add permission with rule to role
            $auth->addChild($author, $own);
        }
    }

    /**
     * @inheritdoc
     */
    public function menu(&$items)
    {
        if (!Yii::$app->user->can('admin')) {
            return;
        }

        $items['user'] = [
            'label' => '<i class="fas fa-unlock-alt"></i> ' . Html::encode(Yii::t('user', 'Security')),
            'encode' => false,
            'items' => [
                ['label' => Yii::t('user', 'Permissions'), 'url' => ['/user/permission/index']],
                ['label' => Yii::t('user', 'Roles'), 'url' => ['/user/role/index']],
                ['label' => Yii::t('user', 'Users'), 'url' => ['/user/user/index']],
            ],
        ];
    }

    /**
     * Making user menu
     * @return array
     */
    public function userMenu()
    {
        self::translation();

        if (Yii::$app->user->isGuest) {
            return [];
        }

        $username = Html::encode(Yii::$app->getUser()->getUsername());

        return [
            'label' => '<i class="fas fa-user"></i><span class="d-none d-sm-inline"> ' . $username . '</span>',
            'encode' => false,
            'dropDownOptions' => ['class' => 'dropdown-menu-right'],
            'items' => [
                ['label' => Yii::t('user', 'Settings'), 'url' => ['/user/settings/index']],
                ['label' => Yii::t('user', 'Change password'), 'url' => ['/user/password/index']],
                '<div class="dropdown-divider"></div>',
                ['label' => Yii::t('user', 'Logout'), 'url' => ['/user/logout/index']],
            ],
        ];
    }

}
