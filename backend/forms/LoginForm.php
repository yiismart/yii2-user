<?php

namespace smart\user\backend\forms;

use Yii;
use smart\base\Form;
use smart\user\models\User;

class LoginForm extends Form
{

    /**
     * @var string user e-mail
     */
    public $email;

    /**
     * @var string user password
     */
    public $password;

    /**
     * @var boolean remember me option
     */
    public $rememberMe;

    /**
     * @var User
     */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'E-mail'),
            'password' => Yii::t('user', 'Password'),
            'rememberMe' => Yii::t('user', 'Remember me'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['email', 'validateActive'],
        ];
    }

    /**
     * Check that user is active
     * @param string $attribute 
     * @param array $params 
     * @return void
     */
    public function validateActive($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if ($user && !$user->active) {
                $this->addError($attribute, Yii::t('user', 'User is blocked.'));
            }
        }
    }

    /**
     * Check password
     * @param string $attribute 
     * @param array $params 
     * @return void
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('user', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Login
     * @return boolean
     */
    public function login()
    {
        if (!$this->validate()) {
            return false;
        }

        return $this->getUser()->login($this->rememberMe ? 3600*24*30 : 0);
    }

    /**
     * User getter
     * @return User
     */
    public function getUser()
    {
        if ($this->_user !== false) {
            return $this->_user;
        }

        return $this->_user = User::findByEmail($this->email);
    }

}
