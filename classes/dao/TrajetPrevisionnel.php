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
use covoiturage\classes\metier\Group as GroupBO;
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\PassagerPrevisionnel as PassagerPrevisionnelBO;

// Helpers
use covoiturage\utils\HDatabase;
use DateTime;

/**
 * Description of TrajetPrevisionnel
 *
 * @author bruno
 */
class TrajetPrevisionnel extends ClasseTable {
    /**
     * @var integer
     */
    public $group_id;
    /**
     * @var integer
     */
    public $conducteur_id;
    /**
     * @var string
     */
    public $date;
    /**
     * @var int
     */
    public $type;

    /**
     * Table covoiturages
     */
    public static function defineTable() {
        $champs[] = ChampTable::getPrimaire('id');
        $champs[] = ChampTable::getFk('group_id', Group::getTable());
        $champs[] = ChampTable::getFk('conducteur_id', User::getTable());
        $champs[] = ChampTable::getPersiste('date', 'datetime', true, true);
        $champs[] = ChampTable::getPersiste('type', 'tinyint', true, true, 1);
        return new Table('trajet_previsionnel', $champs);
    }

    /**
     * @return UserBO
     */
    public function getConducteur() {
        return new UserBO($this->conducteur_id);
    }

    /**
     * @return GroupBO
     */
    public function getGroup() {
        return new GroupBO($this->group_id);
    }

    /**
     * Liste des passagers d'un covoiturage
     * @return PassagerPrevisionnelBO[]
     */
    public function getListePassagers() {
        $sql = PassagerPrevisionnel::getSqlSelect();
        $sql .= ' WHERE trajet_previsionnel_id = ?';
        return PassagerPrevisionnelBO::getListe($sql, [$this->id]);
    }

    /**
     * Un covoiturage a-t-il déjà été effectué
     * Recherche par date, type et groupe
     * @return boolean
     */
    public function isDejaPresent() {
        $sql = 'SELECT * FROM trajet_previsionnel WHERE group_id = ? AND date = ? AND type = ?';
        $params = [$this->group_id, $this->transformerValeurPourBdd('date'), $this->type];
        if ($this->existe()) {
            $sql .= ' AND id != ?';
            $params[] = $this->id;
        }
        $result = HDatabase::rechercher($sql, $params);
        return !empty($result);
    }

    /**
     * Transformation des dates au format fr
     * @param string $attribut
     * @param mixed $value
     * @return mixed
     */
    protected function transformerValeurFromBdd($attribut, $value) {
        if ($attribut == 'date') {
            return date('d/m/Y', strtotime($value));
        }
        return parent::transformerValeurFromBdd($attribut, $value);
    }

    /**
     * Transformation des dates avant envoi en BDD
     * @param string $attribut
     * @return mixed
     */
    protected function transformerValeurPourBdd($attribut) {
        if ($attribut == 'date') {
            $date = DateTime::createFromFormat('d/m/Y', $this->$attribut);
            return $date->format('Y-m-d');
        }
        return parent::transformerValeurPourBdd($attribut);
    }

}
