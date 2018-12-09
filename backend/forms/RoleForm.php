<?php

namespace smart\user\backend\forms;

use Yii;

class RoleForm extends RbacForm
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
     * @var string
     */
    public $roles = [];

    /**
     * @var array
     */
    public $permissions = [];

    /**
     * @var array
     */
    public $users = [];

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Name'),
            'description' => Yii::t('user', 'Description'),
            'roles' => Yii::t('user', 'Roles'),
            'permissions' => Yii::t('user', 'Permissions'),
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
            [['roles', 'permissions'], 'each', 'rule' => ['string']],
            ['users', 'each', 'rule' => ['integer']],
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
        $this->roles = array_map(function ($item) {
            return $item->name;
        }, $object->roles);
        $this->permissions = array_map(function ($item) {
            return $item->name;
        }, $object->permissions);
        $this->users = $object->users;

        $this->_name = $object->name;
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object)
    {
        $object->name = $this->name;
        $object->description = $this->description;
    }

}
