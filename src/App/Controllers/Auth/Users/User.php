<?php

namespace App\Controllers\Auth\Users;

use App;

class User {

    public $id;

    public $firstName;
    public $lastName;
    public $address;
    public $email;
    public $phone;
    public $level;
    public $weight;
    public $password;
    public $date_inscription;
    public $cotisation;
    public $salaire;
    public $experience;
    public $isAdmin;

    public function __construct($id, $firstName, $lastName, $address, $email, $phone, $level, $weight, $password, $date_inscription, $cotisation, $isAdmin = false){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;
        $this->email = $email;
        $this->phone = $phone;
        $this->level = $level;
        $this->weight = $weight;
        $this->password = $password;
        $this->date_inscription = $date_inscription;
        $this->cotisation = $cotisation;
        $this->isAdmin = $isAdmin;
    }

    public function isInstructor() {
        return false;
    }

    public function getLevel() {
        switch ($this->level) {
            case 1:
                return "Débutant";
            case 2:
                return "Intermédiaire";
            case 3:
                return "Avancé";
            default:
                return "Niveau inconnu";
        }
    }
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    public function register(){
        $this->hashPassword();
        $query = App::getApp()->getDB()->prepare('INSERT INTO PERSONNE (nom, prenom, adresse, telephone, email, mdp, poids, niveau) VALUES (:nom, :prenom, :adresse, :telephone, :email, :mdp, :poids, :niveau)');
        $query->execute(array(':nom' => $this->lastName, ':prenom' => $this->firstName, ':adresse' => $this->address, ':telephone' => $this->phone, ':email' => $this->email, ':mdp' => $this->password, ':poids' => $this->weight, ':niveau' => $this->level));
    }

    public function estPaye() {
        return $this->cotisation === 1;
    }

    public function checkEstPaye(){
        if ($this->cotisation === 1){
            return true;
        }
        return false;
    }

    public function setCotisation(){
        $query = App::getApp()->getDB()->prepare('UPDATE PERSONNE SET cotisation=true WHERE id_p = :id_p');
        $query->execute(array(':id_p' => $this->id));
        return true;
    }

    public function updateDatabase() {
        $query = App::getApp()->getDB()->prepare(
            'UPDATE PERSONNE 
            SET nom = :nom, 
                prenom = :prenom, 
                adresse = :adresse, 
                telephone = :telephone, 
                email = :email, 
                poids = :poids, 
                niveau = :niveau, 
                mdp = :mdp 
            WHERE id_p = :id_p'
        );
    
        $query->execute(array(
            ':nom' => $this->lastName,
            ':prenom' => $this->firstName,
            ':adresse' => $this->address,
            ':telephone' => $this->phone,
            ':email' => $this->email,
            ':poids' => $this->weight,
            ':niveau' => $this->level,
            ':mdp' => $this->password,
            ':id_p' => $this->id
        ));
    }
}

?>