<?php

namespace smart\user\backend\forms;

use Yii;
use smart\base\Form;

class PasswordChangeForm extends Form
{
    /**
     * @var string old password
     */
    public $oldPassword;

    /**
     * @var string new password
     */
    public $password;

    /**
     * @var string new password confirm
     */
    public $confirm;

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
            'oldPassword' => Yii::t('user', 'Current password'),
            'password' => Yii::t('user', 'New password'),
            'confirm' => Yii::t('user', 'Confirm'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['oldPassword', function($attribute) {
                if (!$this->hasErrors()) {
                    if (!$this->_object->validatePassword($this->$attribute)) {
                        $this->addError($attribute, Yii::t('user', 'The password is entered incorrectly.'));
                    }
                }
            }],
            ['password', 'string', 'min' => 4],
            ['confirm', 'compare', 'compareAttribute' => 'password'],
            [['oldPassword', 'password', 'confirm'], 'required'],
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
        $object->passwordChange = false;
    }
}
