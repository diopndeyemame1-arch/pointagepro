<?php


class ProfilModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM utilisateurs WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $prenom, $nom, $email, $telephone)
    {
        $sql = "UPDATE utilisateurs
                SET prenom = ?, nom = ?, email = ?, telephone = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $prenom,
            $nom,
            $email,
            $telephone,
            $id
        ]);
    }
}