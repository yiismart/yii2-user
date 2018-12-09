<?php

namespace smart\user\backend\models;

use Yii;
use yii\base\Model;

class Permission extends Model
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
     * @var boolean
     */
    public $own;

    /**
     * @var yii\rbac\Permission
     */
    public $item;

    /**
     * Find
     * @param string $name 
     * @return static
     */
    public static function find($name)
    {
        $auth = Yii::$app->getAuthManager();

        $item = $auth->getPermission($name);
        if ($item === null) {
            return null;
        }

        $ownItem = $auth->getPermission('own');

        return new static([
            'name' => $item->name,
            'description' => $item->description,
            'own' => $ownItem && $auth->hasChild($ownItem, $item),
            'item' => $item,
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

        $item = $auth->createPermission($this->name);
        $item->description = $this->description;

        if (!$auth->add($item)) {
            return false;
        }

        $this->item = $item;
        return $this->updateOwn();
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

        return $this->updateOwn();
    }

    /**
     * Update allow to author permission link
     * @return boolean
     */
    protected function updateOwn()
    {
        $auth = Yii::$app->getAuthManager();

        $ownItem = $auth->getPermission('own');
        if ($ownItem) {
            $oldOwn = $auth->hasChild($ownItem, $this->item);
            if ($this->own) {
                if (!$oldOwn) {
                    $auth->addChild($ownItem, $this->item);
                }
            } else {
                if ($oldOwn) {
                    $auth->removeChild($ownItem, $this->item);
                }
            }
        }

        return true;
    }

}
