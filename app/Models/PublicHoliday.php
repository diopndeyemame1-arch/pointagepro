<?php

class PublicHoliday
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Tous les jours fériés
    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM public_holidays ORDER BY holiday_date ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Total
   public function countAll($month = null)
{
    if ($month) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS total
            FROM public_holidays
            WHERE EXTRACT(MONTH FROM holiday_date)::int = :month
        ");
        $stmt->execute([
            ':month' => (int) $month
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    return $this->pdo->query("SELECT COUNT(*) AS total FROM public_holidays")
                     ->fetch(PDO::FETCH_ASSOC)['total'];
}

    // Ce mois
    public function countThisMonth()
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*) FROM public_holidays
            WHERE EXTRACT(MONTH FROM holiday_date) = EXTRACT(MONTH FROM CURRENT_DATE)
            AND EXTRACT(YEAR FROM holiday_date) = EXTRACT(YEAR FROM CURRENT_DATE)
        ");

        return $stmt->fetchColumn();
    }

    // Prochain férié
    public function nextHoliday()
    {
        $stmt = $this->pdo->query("
            SELECT * FROM public_holidays
            WHERE holiday_date >= CURRENT_DATE
            ORDER BY holiday_date ASC
            LIMIT 1
        ");

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Restants
    public function remaining()
    {
        $stmt = $this->pdo->query("
            SELECT COUNT(*) FROM public_holidays
            WHERE holiday_date >= CURRENT_DATE
        ");

        return $stmt->fetchColumn();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO public_holidays (holiday_name, holiday_date, holiday_type)
            VALUES (:name, :date, :type)
        ");
    
        return $stmt->execute([
            ':name' => $data['holiday_name'],
            ':date' => $data['holiday_date'],
            ':type' => $data['holiday_type']
        ]);
    }

    public function getById($id)
    {
            $stmt = $this->pdo->prepare("SELECT * FROM public_holidays WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
    }
    public function delete($id)
    {
            $stmt = $this->pdo->prepare("DELETE FROM public_holidays WHERE id = ?");
            return $stmt->execute([$id]);
    }
    public function update($id, $name, $date, $type, $description)
    {
            $status = ($date < date('Y-m-d')) ? 'passe' : 'avenir';
            $sql = "UPDATE public_holidays 
                    SET holiday_name = ?, 
                        holiday_date = ?, 
                        holiday_type = ?, 
                        description = ?,
                        status = ?
                    WHERE id = ?";
        
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$name, $date, $type, $description, $status, $id]);
    }
    public function isHoliday($date)
    {
        $sql = "SELECT COUNT(*) FROM public_holidays WHERE holiday_date = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date]);
    
        return $stmt->fetchColumn() > 0;
    }
    public function getNextHoliday()
{
    $stmt = $this->pdo->query("
        SELECT *
        FROM public_holidays
        WHERE holiday_date >= CURRENT_DATE
        ORDER BY holiday_date ASC
        LIMIT 1
    ");

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function countRemaining()
{
    $stmt = $this->pdo->query("
        SELECT COUNT(*) 
        FROM public_holidays
        WHERE holiday_date >= CURRENT_DATE
    ");

    return $stmt->fetchColumn();
}

    // Pagination des jours fériés
    public function getPaginated($limit, $offset, $month = null)
    {
        if ($month) {
            $stmt = $this->pdo->prepare("
                SELECT *
                FROM public_holidays
                WHERE EXTRACT(MONTH FROM holiday_date)::int = :month
                ORDER BY holiday_date DESC
                LIMIT :limit OFFSET :offset
            ");

            $stmt->bindValue(':month', (int) $month, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM public_holidays
            ORDER BY holiday_date DESC
            LIMIT :limit OFFSET :offset
        ");
    
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
