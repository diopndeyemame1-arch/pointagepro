<?php

class CompanySettings
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get()
    {
        $stmt = $this->pdo->query("SELECT * FROM company_settings LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($name, $email)
    {
        $stmt = $this->pdo->prepare("
            UPDATE company_settings 
            SET company_name = ?, company_email = ? 
            WHERE id = 1
        ");

        return $stmt->execute([$name, $email]);
    }
    
}