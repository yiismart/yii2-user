<?php

namespace smart\user\backend\forms;

use Yii;
use smart\base\Form;

class RbacForm extends Form
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * @inheritdoc
     */
    public function assignFrom($object, $attributeNames = null)
    {
        parent::assignFrom($object, $attributeNames = null);

        // Rbac form validation
        $this->_name = $object->name;
    }

    /**
     * Name validator
     * @param string $attribute 
     * @param array $params 
     * @param yii\validators\InlineValidator $validator 
     * @return void
     */
    public function validateName($attribute, $params, $validator)
    {
        $auth = Yii::$app->getAuthManager();
        $name = $this->$attribute;
        if ($this->_name != $this->name && ($auth->getPermission($name) !== null || $auth->getRole($name) !== null)) {
            $this->addError($attribute, Yii::t('user', "{s} is already in use.", ['s' => $this->getAttributeLabel($attribute)]));
        }
    }
}
