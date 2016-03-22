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
}
