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
use covoiturage\classes\metier\UserGroup as UserGroupBO;

/**
 * Description of Group
 *
 * @author bruno
 */
class Group extends ClasseTable {
    /**
     * @var string
     */
    public $nom;

    /**
     * Table group
     */
    public static function defineTable() {
        $champs[] = ChampTable::getPrimaire('id');
        $champs[] = ChampTable::getPersiste('nom', 'varchar', true, true, 128);
        return new Table('group', $champs);
    }

    /**
     * Liste des user d'un groupe
     * @return UserGroupBO[]
     */
    public function getListeUserGroup() {
        $sql = UserGroup::getSqlSelect();
        $sql .= ' WHERE group_id = ?';
        return UserGroup::getListe($sql, [$this->id]);
    }
}
