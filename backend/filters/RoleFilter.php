<?php

namespace smart\user\backend\filters;

use Yii;
use yii\data\ArrayDataProvider;
use yii\rbac\Item;
use smart\base\FilterInterface;
use smart\user\backend\models\Role;

class RoleFilter extends Role implements FilterInterface
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Name'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDataProvider($config = [])
    {
        $auth = Yii::$app->getAuthManager();

        $items = [];
        foreach ($auth->getRoles() as $item) {
            if ($item->name == 'author') {
                continue;
            }

            // Roles and permissions
            $roles = $permissions = [];
            foreach ($auth->getChildren($item->name) as $child) {
                if ($child->type == Item::TYPE_ROLE) {
                    $roles[] = $child;
                } else {
                    $permissions[] = $child;
                }
            }

            $items[] = new static([
                'name' => $item->name,
                'description' => $item->description,
                'roles' => $roles,
                'permissions' => $permissions,
            ]);
        }

        $config['allModels'] = $items;
        $config['modelClass'] = static::className();

        return new ArrayDataProvider(array_replace([
            'pagination' => false,
        ], $config));
    }
}
