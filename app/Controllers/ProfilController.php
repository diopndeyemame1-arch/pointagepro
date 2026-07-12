<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/ProfilModel.php';

class ProfilController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new ProfilModel($pdo);
    }

    /**
     * Affichage du profil
     */
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {

            header("Location: ../Views/auth/login.php");
            exit;

        }

        $user = $this->model->getUserById($_SESSION['user_id']);

        require __DIR__ . '/../Views/settings/profil.php';
    }

    /**
     * Mise à jour du profil
     */
    public function update()
    {
        if (!isset($_SESSION['user_id'])) {

            header("Location: ../Views/auth/login.php");
            exit;

        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $firstname = trim($_POST['firstname']);
            $lastname  = trim($_POST['lastname']);
            $email     = trim($_POST['email']);
            $phone     = trim($_POST['phone']);

            $this->model->updateUser(

                $_SESSION['user_id'],
                $firstname,
                $lastname,
                $email,
                $phone

            );

            header("Location: ../Views/settings/profil.php?success=1");
            exit;
        }
    }
}

/**
 * ==========================
 * Exécution du contrôleur
 * ==========================
 */

$controller = new ProfilController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $controller->update();

} else {

    $controller->index();

}