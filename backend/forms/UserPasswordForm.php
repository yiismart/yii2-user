<?php

namespace smart\user\backend\forms;

use Yii;
use smart\base\Form;

class UserPasswordForm extends Form
{
    /**
     * @var string new password
     */
    public $password;

    /**
     * @var string new password confirm
     */
    public $confirm;

    /**
     * @var boolean
     */
    public $passwordChange;

    /**
     * @var smart\user\models\User
     */
    private $_object;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('user', 'New password'),
            'confirm' => Yii::t('user', 'Confirm'),
            'passwordChange' => Yii::t('user', 'User must change password at next login'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'string', 'min' => 4],
            ['confirm', 'compare', 'compareAttribute' => 'password'],
            ['passwordChange', 'boolean'],
            [['password', 'confirm'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function assignFrom($object)
    {
        $this->_object = $object;
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object)
    {
        $object->setPassword($this->password);
        $object->passwordChange = self::toBoolean($this->passwordChange);
    }
}
