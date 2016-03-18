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

use covoiturage\utils\HSession;
use covoiturage\utils\HDatabase;

//DO
use covoiturage\classes\metier\UserGroup as UserGroupBO;
use covoiturage\classes\metier\Covoiturage as CovoiturageBO;
use covoiturage\classes\metier\Passager as PassagerBO;
use covoiturage\classes\metier\User as UserBO;
use covoiturage\classes\metier\Group as GroupBO;

/**
 * Description of User
 *
 * @author bruno
 */
class User extends ClasseTable {
    /**
     * @var string
     */
    public $nom;
    /**
     * @var string
     */
    public $prenom;
    /**
     * @var string
     */
    public $tel;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var boolean
     */
    public $admin;

    /**
     * Table user
     */
    public static function defineTable() {
        $champs[] = ChampTable::getPrimaire('id');
        $champs[] = ChampTable::getPersiste('nom', 'varchar', true, true, 128);
        $champs[] = ChampTable::getPersiste('prenom', 'varchar', true, true, 128);
        $champs[] = ChampTable::getPersiste('tel', 'varchar', false, false, 16);
        $champs[] = ChampTable::getPersiste('email', 'varchar', false, false, 128);
        $champs[] = ChampTable::getPersiste('password', 'varchar', true, true, 128);
        $champs[] = ChampTable::getPersiste('admin', 'tinyint', false, false, 1);
        return new Table('user', $champs);
    }

    /**
     * Liste des groupes d'un user
     * @return UserGroupBO[]
     */
    public function getListeUserGroup() {
        $sql = UserGroup::getSqlSelect();
        $sql .= ' WHERE user_id = ?';
        return UserGroupBO::getListe($sql, [$this->id]);
    }

    /**
     * Liste des covoiturage dont le conducteur est l'utilisateur
     * @return int|CovoiturageBO[]
     */
    public function getListeCovoiturage($group = NULL, $userPassager = NULL, $nbMax = 0, $page = 1, $mode = self::MODE_NORMAL, $checkAdmin = TRUE) {
        $params = [];
        $select = Covoiturage::getSqlSelect();
        $from = '';
        $where = ' WHERE 1';
        if (!$checkAdmin || !$this->admin || ($userPassager instanceof UserBO && $userPassager->existe())) {
            $where .= ' AND conducteur_id = ?';
            $params = [$this->id];
        }
        if ($group instanceof GroupBO && $group->existe()) {
            $where .= ' AND group_id = ?';
            $params[] = $group->id;
        }
        if ($userPassager instanceof UserBO && $userPassager->existe()) {
            $from .= ' INNER JOIN passager ON (passager.covoiturage_id = covoiturage.id)';
            $where .= ' AND passager.user_id = ?';
            $params[] = $userPassager->id;
        }
        $order = ' ORDER BY date DESC, type DESC';
        $sql = $select . $from . $where . $order;
        return CovoiturageBO::getListe($sql, $params, $nbMax, $page, $mode);
    }

    /**
     * Liste des covoiturage dont l'utilisateur était passager
     * @return int|CovoiturageBO[]
     */
    public function getListeCovoituragePassager($group = NULL, $userConducteur = NULL, $nbMax = 0, $page = 1, $mode = self::MODE_NORMAL) {
        $select = Covoiturage::getSqlSelect();
        $from = '';
        $where = ' WHERE 1';
        $order = ' ORDER BY date DESC, type DESC';
        $params = [];
        if ($group instanceof GroupBO && $group->existe()) {
            if (!$this->admin || ($userConducteur instanceof UserBO && $userConducteur->existe())) {
                $from .= ' INNER JOIN passager ON (passager.covoiturage_id = covoiturage.id)';
            }
            $where .= ' AND group_id = ? AND passager.user_id = ?';
            $params[] = $group->id;
            $params[] = $this->id;
        }
        if ($userConducteur instanceof UserBO && $userConducteur->existe()) {
            $where .= ' AND conducteur_id = ?';
            $params[] = $userConducteur->id;
        }
        $sql = $select . $from . $where . $order;
        return CovoiturageBO::getListe($sql, $params, $nbMax, $page, $mode);
    }

    /**
     * Liste des trajets durant lesquels l'utilisateur était passager
     * @return PassagerBO[]
     */
    public function getListePassager() {
        $sql = Passager::getSqlSelect();
        $sql .= ' WHERE user_id = ?';
        return PassagerBO::getListe($sql, [$this->id]);
    }

    public static function chargerParNomEtPrenom($nom, $prenom) {
        $sql = static::getSqlSelect();
        $sql .= ' WHERE nom = :nom AND prenom = :prenom';
        $liste = static::getListe($sql, [':nom' => $nom, ':prenom' => $prenom]);
        if (empty($liste)) {
            $user = new UserBO();
            $user->nom = $nom;
            $user->prenom = $prenom;
            return $user;
        } else {
            return $liste[0];
        }
    }
    
    public static function connecter($email, $password) {
        $sql = static::getSqlSelect();
        $sql .= ' WHERE email = :email';
        $liste = static::getListe($sql, [':email' => $email]);
        if (!empty($liste)) {
            foreach ($liste as $user) {
                if ($user->checkPassword($password)) {
                    HSession::setUser($user);
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    protected function transformerValeurFromBdd($attribut, $value) {
        if ($attribut == 'admin') {
            return ($value == 1);
        }
        return parent::transformerValeurFromBdd($attribut, $value);
    }

    protected function transformerValeurPourBdd($attribut) {
        switch ($attribut) {
            case 'admin':
                return $this->$attribut ? 1 : 0;
            default:
                return parent::transformerValeurPourBdd($attribut);
        }
    }

    public function getCreditsConducteur($idGroup) {
        $sql = 'SELECT COUNT(*) nb
                FROM `passager` AS p
                INNER JOIN `covoiturage` AS c ON (c.id = p.covoiturage_id)
                WHERE c.conducteur_id = ? AND c.group_id = ?';
        $result = HDatabase::rechercher($sql, [$this->id, $idGroup]);
        return $result[0]['nb'];
    }

    public function getCreditsPassager($idGroup) {
        $sql = 'SELECT COUNT(*) nb
                FROM `passager` AS p
                INNER JOIN `covoiturage` AS c ON (c.id = p.covoiturage_id)
                WHERE p.user_id = ? AND c.group_id = ?';
        $result = HDatabase::rechercher($sql, [$this->id, $idGroup]);
        return $result[0]['nb'];
    }
}
