<?php
class AbsenceModel{
    private $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
public function create($data){
        $sql="INSERT INTO absences(user_id, type,start_date, end_date, duration, reason) VALUES(:user_id, :type,:start_date, :end_date, :duration, :reason) RETURNING id";
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchColumn();
    }

    public function getByIdWithStudent($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT a.*, u.firstname, u.lastname, u.email
             FROM absences a
             INNER JOIN users u ON u.id = a.user_id
             WHERE a.id = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAdminRecipients()
    {
        $stmt = $this->pdo->query(
            "SELECT firstname, lastname, email
             FROM users
             WHERE role = 'admin' AND email IS NOT NULL AND email <> ''"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll($user_id, $limit, $offset)
{
    $sql = "SELECT *
            FROM absences
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $this->pdo->prepare($sql);

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function countAbsences($user_id)
{
    $sql = "SELECT COUNT(*) AS total
            FROM absences
            WHERE user_id = :user_id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $user_id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
public function countApprovedAbsences($user_id)
{
    $sql = "SELECT COUNT(*) AS total
            FROM absences
            WHERE user_id = :user_id
            AND status = 'approuve'";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'user_id' => $user_id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

public function countPendingAbsences($user_id)
{
    $sql = "SELECT COUNT(*) AS total
            FROM absences
            WHERE user_id = :user_id
            AND status = 'en_attente'";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'user_id' => $user_id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

public function countRefusedAbsences($user_id)
{
    $sql = "SELECT COUNT(*) AS total
            FROM absences
            WHERE user_id = :user_id
            AND status = 'refuse'";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'user_id' => $user_id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
//admin
public function totalAbsences()
{
    $sql = "SELECT COUNT(*) AS total FROM absences";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
public function totalApprovedAbsences()
{
    $sql = "SELECT COUNT(*) AS total
            FROM absences
            WHERE status = 'approuve'";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
public function totalPendingAbsences()
{
    $sql = "SELECT COUNT(*) AS total
            FROM absences
            WHERE status = 'en_attente'";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
public function totalRefusedAbsences()
{
    $sql = "SELECT COUNT(*) AS total
            FROM absences
            WHERE status = 'refuse'";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
//admin
public function getAllAdmin($limit, $offset, $department = null, $cohort = null, $search = null)
{
    $sql = "SELECT
                absences.id,
                absences.type,
                absences.start_date,
                absences.end_date,
                absences.duration,
                absences.reason,
                absences.status,
                users.firstname,
                users.lastname,
                departments.name AS department,
                cohorts.name AS cohort
            FROM absences
            INNER JOIN users
                ON absences.user_id = users.id
            LEFT JOIN departments
                ON users.department_id = departments.id
            LEFT JOIN cohorts
                ON users.cohort_id = cohorts.id
            WHERE 1=1";

    // Tableau des paramètres
    $params = [];

    // Filtre département
    if (!empty($department)) {
        $sql .= " AND departments.id = :department";
        $params['department'] = $department;
    }

    // Filtre cohorte
    if (!empty($cohort)) {
        $sql .= " AND cohorts.id = :cohort";
        $params['cohort'] = $cohort;
    }
    // Recherche étudiant
if (!empty($search)) {

    $sql .= " AND (
        users.firstname ILIKE :search
        OR users.lastname ILIKE :search
    )";

    $params['search'] = "%".$search."%";
}

    // Pagination
    $sql .= " ORDER BY absences.created_at DESC
              LIMIT :limit OFFSET :offset";

    $stmt = $this->pdo->prepare($sql);

    // On lie les paramètres des filtres
   foreach ($params as $key => $value) {

    if ($key == 'search') {
        $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
    } else {
        $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
    }

}

    // Pagination
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function countAllAbsences()
{
    $sql = "SELECT COUNT(*) AS total FROM absences";

    $stmt = $this->pdo->query($sql);

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}
//bouton
public function approve($id)
{
    $sql = "UPDATE absences
            SET status='approuve',
                updated_at=NOW()
            WHERE id=:id";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'id'=>$id
    ]);
}

public function refuse($id)
{
    $sql = "UPDATE absences
            SET status='refuse',
                updated_at=NOW()
            WHERE id=:id";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        'id'=>$id
    ]);
}
//filtrer
public function getDepartments()
{
    $sql = "SELECT id, name
            FROM departments
            ORDER BY name";

    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function getCohorts()
{
    $sql = "SELECT id, name
            FROM cohorts
            ORDER BY name";

    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
public function countAllAbsencesFiltered($department = null, $cohort = null, $search = null)
{
    $sql = "SELECT COUNT(*) AS total
            FROM absences
            INNER JOIN users
                ON absences.user_id = users.id
            LEFT JOIN departments
                ON users.department_id = departments.id
            LEFT JOIN cohorts
                ON users.cohort_id = cohorts.id
            WHERE 1=1";

    $params = [];

    if (!empty($department)) {
        $sql .= " AND departments.id = :department";
        $params['department'] = $department;
    }

    if (!empty($cohort)) {
        $sql .= " AND cohorts.id = :cohort";
        $params['cohort'] = $cohort;
    }
    if (!empty($search)) {

    $sql .= " AND (
        users.firstname ILIKE :search
        OR users.lastname ILIKE :search
    )";

    $params['search'] = "%".$search."%";
}

    $stmt = $this->pdo->prepare($sql);
foreach ($params as $key => $value) {

    if ($key == 'search') {
        $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
    } else {
        $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
    }

}

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

}
