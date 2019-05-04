<?php

namespace smart\user\backend\forms;

use Yii;
use smart\base\Form;

class SettingsForm extends Form
{
    /**
     * @var string e-mail
     */
    private $_email;

    /**
     * @var string first name
     */
    public $firstName;

    /**
     * @var string last name
     */
    public $lastName;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'E-mail'),
            'firstName' => Yii::t('user', 'First name'),
            'lastName' => Yii::t('user', 'Last name'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function assignFrom($object)
    {
        $this->firstName = self::fromString($object->firstName);
        $this->lastName = self::fromString($object->lastName);

        $this->_email = $object->email;
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object)
    {
        $object->firstName = self::toString($this->firstName);
        $object->lastName = self::toString($this->lastName);
    }

    /**
     * E-mail getter
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }
}
