<?php

class ProfilModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer les informations de l'étudiant connecté
     */
    public function getUserById($id)
    {
        $sql = "
            SELECT
                u.id,
                u.firstname,
                u.lastname,
                u.email,
                u.phone,
                u.photo,
                d.name AS department_id,
                c.name AS cohort_id
            FROM users u
            LEFT JOIN departments d
                ON u.department_id = d.id
            LEFT JOIN cohorts c
                ON u.cohort_id = c.id
            WHERE u.id = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Modifier les informations personnelles
     */
    public function updateUser($id, $firstname, $lastname, $email, $phone)
    {
        $sql = "
            UPDATE users
            SET
                firstname = ?,
                lastname = ?,
                email = ?,
                phone = ?
            WHERE id = ?
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $firstname,
            $lastname,
            $email,
            $phone,
            $id
        ]);
    }

    /**
     * Modifier uniquement la photo
     */
    public function updatePhoto($id, $photo)
    {
        $sql = "UPDATE users SET photo=? WHERE id=?";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$photo, $id]);
    }
}