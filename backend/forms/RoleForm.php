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
    public function map()
    {
        return [
            [['name', 'description'], 'string'],
            ['roles', 'array', 'from' => function($value) {
                return $value->name;
            }, 'to' => function($value) {
                return Yii::$app->getAuthManager()->getRole($value);
            }],
            ['permissions', 'array', 'from' => function($value) {
                return $value->name;
            }, 'to' => function($value) {
                return Yii::$app->getAuthManager()->getPermission($value);
            }],
            ['users', 'array', 'mapper' => 'integer'],
        ];
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
