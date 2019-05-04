<?php

namespace smart\user\forms;

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
        $this->name = self::fromString($object->name);
        $this->description = self::fromString($object->description);
        $this->roles = array_map(function ($item) {
            return self::fromString($item->name);
        }, $object->roles);
        $this->permissions = array_map(function ($item) {
            return self::fromString($item->name);
        }, $object->permissions);
        $this->users = $object->users;

        $this->_name = $object->name;
    }

    /**
     * @inheritdoc
     */
    public function assignTo($object)
    {
        $auth = Yii::$app->getAuthManager();

        $object->name = self::toString($this->name);
        $object->description = self::toString($this->description);
        $object->roles = array_map(function ($name) use ($auth) {
            return $auth->getRole($name);
        }, is_array($this->roles) ? $this->roles : []);
        $object->permissions = array_map(function ($name) use ($auth) {
            return $auth->getPermission($name);
        }, is_array($this->permissions) ? $this->permissions : []);
        $object->users = is_array($this->users) ? $this->users : [];
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

        if ($this->_name !== null && (($item = $auth->getRole($this->_name)) !== null)) {
            foreach ($items as $k => $v) {
                if (!$auth->canAddChild($item, $v)) {
                    unset($items[$k]);
                }
            }
        }

        return array_map(create_function('$v', 'return $v->name;'), $items);
    }

    /**
     * Return available permissions names
     * @return array
     */
    public function getAvailablePermissions()
    {
        $auth = Yii::$app->getAuthManager();

        $items = $auth->getPermissions();
        unset($items['own']);

        return array_map(create_function('$v', 'return $v->name;'), $items);
    }
}
