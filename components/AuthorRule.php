<?php

namespace smart\user\components;

use yii\rbac\Rule;

class AuthorRule extends Rule
{

    /**
     * @var string Name of author rule
     */
    public $name = 'author';

    /**
     * Checking
     * @param type $user 
     * @param type $item 
     * @param type $params 
     * @return boolean
     */
    public function execute($user, $item, $params)
    {
        return isset($params[0]) && ($params[0] instanceof \yii\db\BaseActiveRecord) && $params[0]->hasAttribute('user_id') && $params[0]->user_id == $user;
    }

}
