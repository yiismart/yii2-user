<?php

namespace smart\user\backend\forms;

use Yii;
use smart\user\models\User;

class UserForm extends RbacForm
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
    public $active = true;

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
    public function assignFrom($object)
    {
        $this->admin = $object->admin == 0 ? '0' : '1';
        $this->email = $object->email;
        $this->active = $object->active == 0 ? '0' : '1';
        $this->firstName = $object->firstName;
        $this->lastName = $object->lastName;
        $this->comment = $object->comment;
        $this->roles = $object->roles;
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object)
    {
        $object->admin = $this->admin == 1;
        $object->email = $this->email;
        $object->active = $this->active == 1;
        $object->firstName = $this->firstName;
        $object->lastName = $this->lastName;
        $object->comment = $this->comment;
        $object->roles = is_array($this->roles) ? $this->roles : [];
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
