<?php

namespace App\Views;

use App\Controllers\Auth\Auth;

class Router
{

    private static $template;

    public function __construct()
    {
        self::$template = new Template(ROOT . '/templates');
    }
    
    public static function render(string $view, string $title, array $cssFiles = [])
    {
        self::renderWithTemplate($view, $title, 'main', $cssFiles);
    }

    public static function renderWithTemplate(string $view, string $title, string $layout, array $cssFiles = [])
    {

        ob_start();
        require ROOT . '/templates/' . $view;
        $content = ob_get_clean();

        self::$template->setLayout($layout);
        self::$template->setTitle($title);
        self::$template->setCssFiles($cssFiles);
        self::$template->setContent($content);

        echo self::$template->compile();
    }

    public function execute() {
        if (isset($_GET['action']) && $_GET['action'] !== '') {
            $action = $_GET['action'];
        } else {
            $action = 'home';
        }

        switch ($action) {
            case 'home':
                self::render('home.php', 'Accueil', ['index.css']);
                break;

            case 'login':
                self::render('auth/login.php', 'Connexion', ['form.css']);
                break;
            case 'register':
                self::render('auth/register.php', 'Inscription', ['form.css']);
                break;
            case 'modif_profil':
                Auth::checkUserLoggedIn();
                self::render('auth/modif_profil.php', 'Modif_profil', ['form.css']);
                break;
            case 'logout':
                self::render('auth/logout.php', 'Deconnexion', []);
                break;

            case "planning":
                Auth::checkUserLoggedIn();
                self::render('reservation/planning.php', 'Planning', ['planning.css', 'navigation.css']);
                break;
            case 'coursReserver':
                Auth::checkUserLoggedIn();
                self::render('reservation/coursReserver.php', "Mes Cours", ['planning.css', 'navigation.css']);
                break;
            case 'paiement':
                Auth::checkUserLoggedIn();
                self::render('reservation/pay.php', 'Paiement', ['paiement.css']);
                break;

            case 'dashboard':
                Auth::checkUserIsInstructor();
                self::render('admin/dashboard.php', 'Tableau de bord', ['form.css']);
                break;
            case 'creation_cours':
                Auth::checkUserIsInstructor();
                self::render('admin/creation_cours_p.php', "Création d'un cours", ['form.css', 'full-form.css']);
                break;
            case 'creation_cours_realise':
                Auth::checkUserIsInstructor();
                self::render('admin/creation_cours_r.php', "Création d'un cours", ['form.css', 'full-form.css']);
                break;
            case 'creation_poney':
                Auth::checkUserIsInstructor();
                self::render('admin/creation_pon.php', "Création d'un poney", ['form.css', 'full-form.css']);
                break;
            case 'planningPoney':
                Auth::checkUserIsInstructor();
                self::render('admin/coursPoney.php', "Planning d'un poney", ['planning.css', 'navigation.css']);
                break;
            case 'ajouter_moniteur';
                Auth::checkUserIsAdmin();
                self::renderWithTemplate('admin/ajouter_moniteur.php', "Ajout d'un moniteur", 'main', ['form.css', 'full-form.css']);
                break;
            case 'retirer_moniteur';
                Auth::checkUserIsAdmin();
                self::renderWithTemplate('admin/retirer_moniteur.php', "Suppression d'un moniteur", 'main', ['form.css', 'full-form.css']);
                break;
            default:
                self::render('404.php', 'Page introuvable', ['404.css']);
                break;
        }
    }
}

?>