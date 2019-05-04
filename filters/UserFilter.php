<?php

namespace smart\user\backend\filters;

use Yii;
use yii\data\ActiveDataProvider;
use smart\base\FilterInterface;
use smart\user\models\User;

class UserFilter extends User implements FilterInterface
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'E-mail'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getDataProvider($config = [])
    {
        $query = self::find()->andWhere(['not', ['email' => 'admin']]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        $config['query'] = $query;
        return new ActiveDataProvider($config);
    }

}
