<?php

namespace smart\user\backend\forms;

use Yii;

class PermissionForm extends RbacForm
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var boolean
     */
    public $own;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Name'),
            'description' => Yii::t('user', 'Description'),
            'own' => Yii::t('user', 'Allow to author'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'string', 'max' => 64],
            ['description', 'string'],
            ['own', 'boolean'],
            ['name', 'required'],
            ['name', 'validateName'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function assignFrom($object)
    {
        $this->name = $object->name;
        $this->description = $object->description;
        $this->own = $object->own ? '1' : '0';

        $this->_name = $object->name;
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object)
    {
        $object->name = $this->name;
        $object->description = $this->description;
        $object->own = $this->own == 0 ? false : true;
    }

}
