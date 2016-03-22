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
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\Covoiturage as CovoiturageBO;
use covoiturage\classes\metier\Passager as BO;

/**
 * Description of Passager
 *
 * @author bruno
 */
class Passager extends ClasseTable {
    /**
     * @var integer
     */
    public $user_id;
    /**
     * @var integer
     */
    public $covoiturage_id;

    /**
     * Table passager
     */
    public static function defineTable() {
        $champs[] = ChampTable::getPrimaire('id');
        $champs[] = ChampTable::getFk('user_id', User::getTable());
        $champs[] = ChampTable::getFk('covoiturage_id', Covoiturage::getTable());
        return new Table('passager', $champs);
    }

    /**
     * @return UserBO
     */
    public function getUser() {
        return new UserBO($this->user_id);
    }

    /**
     * @return CovoiturageBO
     */
    public function getCovoiturage() {
        return new CovoiturageBO($this->covoiturage_id);
    }

    /**
     * Chargement d'un passager pour un trajet
     * @param CovoiturageBO $covoiturage
     * @param UserBO $user
     * @return PassagerBO
     */
    public static function chargerParCovoiturageEtUser(CovoiturageBO $covoiturage, UserBO $user) {
        $sql = static::getSqlSelect();
        $sql .= ' WHERE covoiturage_id = :covoiturage_id AND user_id = :user_id';
        $liste = static::getListe($sql, [':covoiturage_id' => $covoiturage->id, ':user_id' => $user->id]);
        if (empty($liste)) {
            $passager = new BO();
            $passager->covoiturage_id = $covoiturage->id;
            $passager->user_id = $user->id;
            return $passager;
        } else {
            return $liste[0];
        }
    }
}
