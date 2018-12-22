<?php

namespace smart\user\backend\models;

use Yii;
use yii\base\Model;

class Role extends Model
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
     * @var yii\rbac\Permission
     */
    public $item;

    /**
     * @var array
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
     * Find
     * @param string $name 
     * @return static
     */
    public static function find($name)
    {
        $auth = Yii::$app->getAuthManager();

        $item = $auth->getRole($name);
        if ($item === null) {
            return null;
        }

        $roles = $permissions = $users = [];
        $items = $item === null ? [] : Yii::$app->getAuthManager()->getChildren($item->name);
        foreach ($items as $value) {
            if ($value->type == $value::TYPE_ROLE) {
                $roles[] = $value;
            } else {
                $permissions[] = $value;
            }
        }

        return new static([
            'name' => $item->name,
            'description' => $item->description,
            'item' => $item,
            'roles' => $roles,
            'permissions' => $permissions,
            'users' => $auth->getUserIdsByRole($item->name),
        ]);
    }

    /**
     * Save
     * @return boolean
     */
    public function save()
    {
        if ($this->item === null) {
            return $this->create();
        } else {
            return $this->update();
        }
    }

    /**
     * Delete
     * @return boolean
     */
    public function delete()
    {
        if ($this->item === null) {
            return false;
        }

        return Yii::$app->getAuthManager()->remove($this->item);
    }

    /**
     * Create
     * @return boolean
     */
    protected function create()
    {
        $auth = Yii::$app->getAuthManager();

        $item = $auth->createRole($this->name);
        $item->description = $this->description;

        if (!$auth->add($item)) {
            return false;
        }

        $this->item = $item;
        $this->updateChildren();
        $this->updateUsers();

        return true;
    }

    /**
     * Update
     * @return boolean
     */
    protected function update()
    {
        if ($this->item === null) {
            return false;
        }

        $auth = Yii::$app->getAuthManager();
        $name = $this->item->name;
        $item = $this->item;

        $item->name = $this->name;
        $item->description = $this->description;

        if (!$auth->update($name, $item)) {
            return false;
        }

        $this->updateChildren();
        $this->updateUsers();

        return true;
    }

    /**
     * Update nested roles and permissions
     * @return boolean
     */
    protected function updateChildren()
    {
        $auth = Yii::$app->getAuthManager();

        // Old
        $old = [];
        foreach ($auth->getChildren($this->name) as $item) {
            $old[$item->name] = $item;
        }

        // New
        $new = [];
        // Roles
        foreach ($this->roles as $item) {
            $new[$item->name] = $item;
        }
        // Permissions
        foreach ($this->permissions as $item) {
            $new[$item->name] = $item;
        }

        // Add new
        foreach (array_diff_key($new, $old) as $item) {
            $auth->addChild($this->item, $item);
        }

        // Remove old
        foreach (array_diff_key($old, $new) as $item) {
            $auth->removeChild($this->item, $item);
        }

        return true;
    }

    protected function updateUsers()
    {
        $auth = Yii::$app->getAuthManager();

        // Old
        $old = [];
        foreach ($auth->getUserIdsByRole($this->name) as $id) {
            $old[$id] = $id;
        }

        // New
        $new = [];
        foreach ($this->users as $id) {
            $new[$id] = $id;
        }

        // Assign new
        foreach (array_diff_key($new, $old) as $id) {
            $auth->assign($this->item, $id);
        }

        // Revoke old
        foreach (array_diff_key($old, $new) as $id) {
            $auth->revoke($this->item, $id);
        }

        return true;
    }

}
