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
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\Covoiturage as CovoiturageBO;

use covoiturage\utils\HDatabase;

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
        $sql = UserGroupBO::getSqlSelect();
        $sql .= ' WHERE group_id = ?';
        return UserGroupBO::getListe($sql, [$this->id]);
    }

    /**
     * Liste des user d'un groupe
     * @return CovoiturageBO[]
     */
    public function getListeCovoiturage() {
        $sql = CovoiturageBO::getSqlSelect();
        $sql .= ' WHERE group_id = ? ORDER BY date DESC';
        return CovoiturageBO::getListe($sql, [$this->id]);
    }

    /**
     * Nb covoiturage dans groupe
     * @return int
     */
    public function countCovoiturages() {
        $sql = CovoiturageBO::getSqlSelect(TRUE);
        $sql .= ' WHERE group_id = ?';
        $resultat = HDatabase::rechercher($sql, [$this->id]);
        return $resultat[0][0];
    }
    
    /**
     * Liste des user d'un groupe
     * @return UserGroupBO[]
     */
    public function getUserGroup(UserBO $user) {
        return UserGroupBO::chargerParGroupeEtUser($this, $user);
    }

    public function getRecapitulatif() {
        $sql = 'SELECT p.user_id, c.conducteur_id, COUNT(p.id) nb FROM `passager` p
                INNER JOIN `covoiturage` c ON (c.id = p.covoiturage_id)
                WHERE c.group_id = ?
                GROUP BY p.user_id, c.conducteur_id';
        return HDatabase::rechercher($sql, [$this->id]);
    }
}
