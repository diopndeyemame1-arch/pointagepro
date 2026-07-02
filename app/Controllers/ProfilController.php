<?php



class ProfilController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
//coumba
    
    public function index($id)
    {
        
        $user = $this->model->getUserById($id);

        
        require '../Views/Settings/profil.php';
    }


    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $telephone = $_POST['telephone'];

            $this->model->updateUser(
                $id,
                $prenom,
                $nom,
                $email,
                $telephone
            );

            
            header("Location: /profil?id=" . $id);
            exit;
        }
    }
}