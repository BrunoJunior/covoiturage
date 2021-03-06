<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace covoiturage\classes\dao;

// Table
use covoiturage\classes\schema\ChampTable;
use covoiturage\classes\schema\Table;
use covoiturage\classes\abstraites\ClasseTable;

// BO
use \covoiturage\classes\metier\Group as GroupBO;
use \covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\UserGroup as UserGroupBO;

/**
 * Description of UserGroupe
 *
 * @author bruno
 */
class UserGroup extends ClasseTable {
    /**
     * @var integer
     */
    public $user_id;
    /**
     * @var integer
     */
    public $group_id;
    /**
     * @var boolean
     */
    public $group_admin;

    /**
     * Table user_group
     */
    public static function defineTable() {
        $champs[] = ChampTable::getPrimaire('id');
        $champs[] = ChampTable::getFk('user_id', User::getTable());
        $champs[] = ChampTable::getFk('group_id', Group::getTable());
        $champs[] = ChampTable::getPersiste('group_admin', 'tinyint', false, false, 1);
        return new Table('user_group', $champs);
    }

    /**
     * @return GroupBO
     */
    public function getGroup() {
        return new GroupBO($this->group_id);
    }

    /**
     * @return UserBO
     */
    public function getUser() {
        return new UserBO($this->user_id);
    }

    /**
     * Chargement par groupe et utilisateur
     * @param GroupBO $group
     * @param UserBO $user
     * @return UserGroupBO
     */
    public static function chargerParGroupeEtUser($group, $user) {
        $sql = static::getSqlSelect();
        $sql .= ' WHERE user_id = :user AND group_id = :group';
        $liste = static::getListe($sql, [':user' => $user->id, ':group' => $group->id]);
        if (empty($liste)) {
            $userGroup = new UserGroupBO();
            $userGroup->group_id = $group->id;
            $userGroup->user_id = $user->id;
            return $userGroup;
        } else {
            return $liste[0];
        }
    }

    /**
     * Transformation des données provenant de la BDD
     * @param string $attribut
     * @param mixed $value
     * @return mixed
     */
    protected function transformerValeurFromBdd($attribut, $value) {
        if ($attribut == 'group_admin') {
            return ($value == 1);
        }
        return parent::transformerValeurFromBdd($attribut, $value);
    }

    /**
     * Transformation des données avant envoi vers la BDD
     * @param string $attribut
     * @return mixed
     */
    protected function transformerValeurPourBdd($attribut) {
        if ($attribut == 'group_admin') {
            return $this->$attribut ? 1 : 0;
        }
        return parent::transformerValeurPourBdd($attribut);
    }
}
