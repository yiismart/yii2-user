<?php

namespace smart\user\backend\filters;

use Yii;
use yii\data\ArrayDataProvider;
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
        $items = [];
        foreach (Yii::$app->getAuthManager()->getRoles() as $name => $item) {
            if ($name == 'author') {
                continue;
            }
            $items[] = new static([
                'name' => $item->name,
                'description' => $item->description,
            ]);
        }

        $config['allModels'] = $items;
        $config['modelClass'] = static::className();

        return new ArrayDataProvider(array_replace([
            'pagination' => false,
        ], $config));
    }

}
