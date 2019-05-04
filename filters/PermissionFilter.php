<?php

namespace smart\user\filters;

use Yii;
use yii\data\ArrayDataProvider;
use smart\base\FilterInterface;
use smart\user\models\Permission;

class PermissionFilter extends Permission implements FilterInterface
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Name'),
            'own' => Yii::t('user', 'Allow to author'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDataProvider($config = [])
    {
        $auth = Yii::$app->getAuthManager();
        $ownItem = $auth->getPermission('own');

        $items = [];
        foreach ($auth->getPermissions() as $name => $item) {
            if ($name == 'own') {
                continue;
            }
            $items[] = new static([
                'name' => $item->name,
                'description' => $item->description,
                'own' => $ownItem && $auth->hasChild($ownItem, $item),
            ]);
        }

        $config['allModels'] = $items;
        $config['modelClass'] = static::className();

        return new ArrayDataProvider(array_replace([
            'pagination' => false,
        ], $config));
    }
}
