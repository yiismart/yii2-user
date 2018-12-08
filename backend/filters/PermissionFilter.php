<?php

namespace smart\user\backend\filters;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use smart\base\FilterInterface;

class PermissionFilter extends Model implements FilterInterface
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
        foreach (Yii::$app->getAuthManager()->getPermissions() as $name => $item) {
            if ($name == 'own') {
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
