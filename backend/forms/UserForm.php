<?php

namespace smart\user\backend\forms;

use Yii;
use smart\base\Form;
use smart\user\models\User;

class UserForm extends Form
{
    /**
     * @var boolean
     */
    public $admin;

    /**
     * @var string
     */
    public $email;

    /**
     * @var boolean
     */
    public $active = 1;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var array
     */
    public $roles = [];

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin' => Yii::t('user', 'Administrator'),
            'email' => Yii::t('user', 'E-mail'),
            'active' => Yii::t('user', 'Active'),
            'firstName' => Yii::t('user', 'First name'),
            'lastName' => Yii::t('user', 'Last name'),
            'comment' => Yii::t('user', 'Comment'),
            'roles' => Yii::t('user', 'Roles'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin', 'active'], 'boolean'],
            ['email', 'string', 'max' => 100],
            ['email', 'email'],
            [['firstName', 'lastName'], 'string', 'max' => 50],
            ['comment', 'string', 'max' => 200],
            ['roles', 'each', 'rule' => ['string']],

            ['email', 'required', 'on' => 'create'],
            ['email', 'unique', 'targetClass' => User::className(), 'on' => 'create'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function map()
    {
        return [
            [['admin', 'active'], 'boolean'],
            [['email', 'firstName', 'lastName', 'comment'], 'string'],
            ['roles', 'array', 'mapper' => 'string'],
        ];
    }

    /**
     * Return available roles names
     * @return array
     */
    public function getAvailableRoles()
    {
        $auth = Yii::$app->getAuthManager();

        $items = $auth->getRoles();
        unset($items['author']);

        return array_map(create_function('$v', 'return $v->name;'), $items);
    }
}
