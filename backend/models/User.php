<?php

namespace smart\user\backend\models;

use Yii;

class User extends \smart\user\models\User
{
    /**
     * @var array
     */
    private $_roles;

    /**
     * Roles getter
     * @return array
     */
    public function getRoles()
    {
        if ($this->_roles !== null) {
            return $this->_roles;
        }

        return $this->_roles = array_keys(Yii::$app->getAuthManager()->getAssignments($this->id));
    }

    /**
     * Roles setter
     * @param array $value 
     * @return void
     */
    public function setRoles($value)
    {
        $this->_roles = $value;
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            if ($success = parent::save($runValidation, $attributeNames)) {
                $this->updateRoles();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $success;
    }

    /**
     * Update roles
     * @return void
     */
    private function updateRoles()
    {
        if ($this->_roles === null) {
            return;
        }
        $auth = Yii::$app->getAuthManager();

        // Old
        $old = [];
        foreach ($auth->getAssignments($this->id) as $name => $item) {
            $old[$name] = $auth->getRole($name);
        }

        // New
        $new = ['author' => $auth->getRole('author')];
        foreach ($this->getRoles() as $name) {
            $new[$name] = $auth->getRole($name);
        }

        // Assign
        foreach (array_diff_key($new, $old) as $item) {
            $auth->assign($item, $this->id);
        }

        // Revoke
        foreach (array_diff_key($old, $new) as $item) {
            $auth->revoke($item, $this->id);
        }

        return true;
    }
}
