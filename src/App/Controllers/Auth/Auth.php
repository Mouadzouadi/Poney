<?php

namespace App\Controllers\Auth;

use App;
use App\Controllers\Auth\Users\Instructor;
use App\Controllers\Auth\Users\User;

class Auth
{

    static function isUserLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    static function isInstructor(): bool
    {
        return isset($_SESSION['is_instructor']) && $_SESSION['is_instructor'];
    }

    static function isAdmin(): bool
    {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }

    static function getCurrentUser(): ?array
    {
        if (self::isUserLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'is_instructor' => $_SESSION['is_instructor'],
                'is_admin' => $_SESSION['is_admin']
            ];
        }
        return null;
    }

    static function getCurrentUserObj() {
        if (self::isUserLoggedIn()) {
            return self::getUserById($_SESSION['user_id']);
        }
        return null;
    }

    static function checkUserLoggedIn() {
        if(!self::isUserLoggedIn()){
            header('Location: /index.php');
        }
    }

    static function checkUserIsInstructor() {
        self::checkUserLoggedIn();
        
        if(!self::isInstructor()) {
            header('Location: /index.php');
        }
    }

    static function checkUserIsAdmin() {
        self::checkUserLoggedIn();
        if(!self::isAdmin()) {
            header('Location: /index.php');
        }
    }

    static function getUserById($id) {
        $query = App::getApp()->getDB()->prepare('SELECT * FROM PERSONNE WHERE id_p = :id_p');
        $query->execute(array(':id_p' => $id));
        $user = $query->fetch();
        return self::createUserObject($user);
    }

    static function getUserByEmail($email) {
        $query = App::getApp()->getDB()->prepare('SELECT * FROM PERSONNE WHERE email = :email');
        $query->execute(array(':email' => $email));
        $user = $query->fetch();
        return self::createUserObject($user);
    }

    private static function createUserObject($user)
    {
        $userObj = null;
        if ($user) {
            if (isset($user['salaire'])) {
                $userObj = new Instructor($user['id_p'], $user['nom'], $user['prenom'], $user['adresse'], $user['email'], $user['telephone'], $user['niveau'], $user['poids'], $user['mdp'], $user['date_inscription'], $user['cotisation'], $user['admin'], $user['salaire'], $user['experience']);
            } else {
                $userObj = new User($user['id_p'], $user['nom'], $user['prenom'], $user['adresse'], $user['email'], $user['telephone'], $user['niveau'], $user['poids'], $user['mdp'], $user['date_inscription'], $user['cotisation'], $user['admin']);
            }
        }
        return $userObj;
    }

}
?>