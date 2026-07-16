<?php
require_once __DIR__ . '/../../config/mail.php';
require_once __DIR__ . '/../Helpers/AuditHelper.php';
class UserController {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  public function store()
{
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null;
    $department_id = !empty($_POST['department_id']) ? $_POST['department_id'] : null;
    $cohort_id = !empty($_POST['cohort_id']) ? $_POST['cohort_id'] : null;
    $role = $_POST['role'];

    // ✅ CHECK EMAIL
    $check = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        header("Location: index.php?page=users&error=email_exists");
exit;
    }
    // If role is student, generate activation token and keep account inactive.
    if (strtolower(trim($role)) === 'etudiant') {
        $token = bin2hex(random_bytes(32));
        $is_active = 0;
        $is_verified = 0;
    } else {
        $token = null;
        $is_active = 1; // admins or other roles active by default
        $is_verified = 1;
    }

    $password = $_POST['password'] ?? null;
    $password_hash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    $photoPath = null;

    if (!empty($_FILES['photo']['name'])) {

        $uploadDir = __DIR__ . '/../../public/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

        move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $fileName);

        $photoPath = 'uploads/' . $fileName;
    }

    $sql = "INSERT INTO users 
    (firstname, lastname, email, phone, department_id, cohort_id, role, photo, activation_token, is_active, is_verified, password_hash)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    RETURNING id";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        $firstname,
        $lastname,
        $email,
        $phone,
        $department_id,
        $cohort_id,
        $role,
        $photoPath,
        $token,
        (int)$is_active,
        (int)$is_verified,
        $password_hash
    ]);

    $createdUserId = $stmt->fetchColumn();
    addAudit($this->pdo, 'CREATE', 'users', $createdUserId);

    // Send activation email only for students
    if ((int)$is_active === 0 && $token) {
        // mail function returns boolean; ignore result but could be logged
        sendActivationMail($email, $firstname . " " . $lastname, $token);
    }
}
public function delete($id = null)
{
    $id = $id ?? ($_GET['id'] ?? null);

    if ($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        addAudit($this->pdo, 'DELETE', 'users', $id);
    }

    header("Location: index.php?page=users");
    exit;
}
public function toggleStatus($id = null)
{
    $id = $id ?? ($_GET['id'] ?? null);

    if ($id) {
        $stmt = $this->pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$id]);
        addAudit($this->pdo, 'UPDATE', 'users', $id);
    }

    header("Location: index.php?page=users");
    exit;
}
public function update($data = null)
{
    $data = $data ?? $_POST;

    $id = $data['id'];

    $firstname = $data['firstname'];
    $lastname = $data['lastname'];
    $email = $data['email'];
    $phone = $data['phone'];
    $department_id = !empty($data['department_id']) ? $data['department_id'] : null;
    $cohort_id = !empty($data['cohort_id']) ? $data['cohort_id'] : null;
    $role = $data['role'];

    $sql = "UPDATE users
            SET firstname=?,
                lastname=?,
                email=?,
                phone=?,
                department_id=?,
                cohort_id=?,
                role=?
            WHERE id=?";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        $firstname,
        $lastname,
        $email,
        $phone,
        $department_id,
        $cohort_id,
        $role,
        $id
    ]);

    addAudit($this->pdo, 'UPDATE', 'users', $id);

    header("Location: index.php?page=users");
    exit;
}
public function import()
{
    if (!isset($_FILES['csv_file']) || !is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        header("Location: index.php?page=users&error=no_file");
        exit;
    }

    $tmp = $_FILES['csv_file']['tmp_name'];
    $file = fopen($tmp, 'r');
    if (!$file) {
        header("Location: index.php?page=users&error=open_fail");
        exit;
    }

    $first = fgetcsv($file, 1000, ';');
    if ($first === false) {
        fclose($file);
        header("Location: index.php?page=users&error=empty_file");
        exit;
    }

    $delimiter = ';';
    if (count($first) <= 1) {
        rewind($file);
        $delimiter = ',';
        $first = fgetcsv($file, 1000, $delimiter);
        if ($first === false) {
            fclose($file);
            header("Location: index.php?page=users&error=empty_file");
            exit;
        }
    }

    $first0 = strtolower(trim($first[0] ?? ''));
    $hasHeader = in_array($first0, ['firstname', 'prenom', 'prénom', 'first name', 'nom', 'name']);
    if (!$hasHeader) {
        rewind($file);
    }

    while (($row = fgetcsv($file, 1000, $delimiter)) !== false) {
        if (count($row) < 3) {
            continue;
        }

        $firstname = trim($row[0] ?? '');
        $lastname  = trim($row[1] ?? '');
        $email     = trim($row[2] ?? '');

        if ($firstname === '' || $lastname === '' || $email === '') {
            continue;
        }

        $check = $this->pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $check->execute([$email]);
        if ($check->fetch()) {
            continue;
        }

        $phone = trim($row[3] ?? null);
        $departmentVal = trim($row[4] ?? '');
        $cohortVal     = trim($row[5] ?? '');
        $role          = strtolower(trim($row[6] ?? 'etudiant'));

        $allowedRoles = ['admin', 'etudiant'];
        if (!in_array($role, $allowedRoles)) {
            $role = 'etudiant';
        }

        $password = trim($row[7] ?? '');
        $photo    = trim($row[8] ?? null);

        $department_id = null;
        if ($departmentVal !== '') {
            if (is_numeric($departmentVal)) {
                $department_id = (int)$departmentVal;
            } else {
                $s = $this->pdo->prepare("SELECT id FROM departments WHERE name = ? LIMIT 1");
                $s->execute([$departmentVal]);
                $dep = $s->fetch(PDO::FETCH_ASSOC);
                $department_id = $dep['id'] ?? null;
            }
        }

        $cohort_id = null;
        if ($cohortVal !== '') {
            if (is_numeric($cohortVal)) {
                $cohort_id = (int)$cohortVal;
            } else {
                $s = $this->pdo->prepare("SELECT id FROM cohorts WHERE name = ? LIMIT 1");
                $s->execute([$cohortVal]);
                $co = $s->fetch(PDO::FETCH_ASSOC);
                $cohort_id = $co['id'] ?? null;
            }
        }

        if ($role === 'etudiant') {
            $activation_token = bin2hex(random_bytes(32));
            $is_active = 0;
            $password_hash = null;
        } else {
            $activation_token = null;
            $is_active = 1;
            $password_hash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO users
            (firstname, lastname, email, phone, photo, department_id, cohort_id, role, password_hash, activation_token, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            RETURNING id"
        );

        $stmt->execute([
            $firstname,
            $lastname,
            $email,
            $phone,
            $photo,
            $department_id,
            $cohort_id,
            $role,
            $password_hash,
            $activation_token,
            (int)$is_active
        ]);

        $createdUserId = $stmt->fetchColumn();
        addAudit($this->pdo, 'CREATE', 'users', $createdUserId);

        if ($role === 'etudiant' && $activation_token) {
            sendActivationMail($email, $firstname . ' ' . $lastname, $activation_token);
        }
    }

    fclose($file);
    header("Location: index.php?page=users");
    exit;
}
}
