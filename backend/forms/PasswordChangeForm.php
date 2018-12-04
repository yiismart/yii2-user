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
            [['oldPassword', 'password'], 'required'],
            ['oldPassword', function($attribute) {
                if (!$this->hasErrors()) {
                    if (!$this->_object->validatePassword($this->$attribute)) {
                        $this->addError($attribute, Yii::t('user', 'The password is entered incorrectly.'));
                    }
                }
            }],
            ['password', 'string', 'min' => 4],
            ['confirm', 'required'],
            ['confirm', 'compare', 'compareAttribute' => 'password'],
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
     * Password change
     * @return boolean
     */
    public function changePassword()
    {
        if (!$this->validate()) {
            return false;
        }
        
        $object = $this->_object;

        $object->setPassword($this->password);
        $object->passwordChange = false;

        return $object->save();
    }

}
