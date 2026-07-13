<?php
// if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once __DIR__.'/../Models/Absence.php';
require_once __DIR__.'/../Helpers/AuditHelper.php';
require_once __DIR__.'/../../config/mail.php';
class AbsController{
    private $absenceModel;
    private $pdo;
    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->absenceModel = new AbsenceModel($pdo);
    }
    public function store(){
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $user_id = $_SESSION['user_id'];

        $data = [
            'user_id'    => $user_id,
            'type'       => $_POST['type'],
            'duration'   => $_POST['duration'],
            'start_date' => $_POST['date_debut'],
            'end_date'   => $_POST['date_fin'],
            'reason'     => $_POST['motif']
        ];
        if (
    empty($_POST['type']) ||
    empty($_POST['duration']) ||
    empty($_POST['date_debut']) ||
    empty($_POST['date_fin'])
) {
    die("Tous les champs sont obligatoires.");
}
           $absenceId = $this->absenceModel->create($data);
           addAudit($this->pdo, 'CREATE', 'absences', $absenceId);

           $absence = $this->absenceModel->getByIdWithStudent($absenceId);
           $studentName = trim(($absence['firstname'] ?? '') . ' ' . ($absence['lastname'] ?? ''));

           foreach ($this->absenceModel->getAdminRecipients() as $admin) {
               $adminName = trim(($admin['firstname'] ?? '') . ' ' . ($admin['lastname'] ?? ''));
               sendAbsenceNotificationMail(
                   $admin['email'],
                   $adminName ?: 'Administrateur',
                   'Nouvelle demande d absence',
                   'Nouvelle demande d absence',
                   'Une demande d absence a ete envoyee par ' . $studentName . '. Connectez-vous a PointagePro pour la traiter.',
                   $absence ?: []
               );
           }

           $_SESSION['absence_success'] = 'Votre demande d absence a ete envoyee aux administrateurs.';
             header("Location: index.php?page=absence");
            exit;
        }
    }
     public function index()
{
    $limit = 5;

    $page = isset($_GET['p']) ? (int) $_GET['p'] : 1;

    if ($page < 1) {
        $page = 1;
    }

    $offset = ($page - 1) * $limit;

    $user_id = $_SESSION['user_id'];

    $absences = $this->absenceModel->getAll($user_id, $limit, $offset);

    $total = $this->absenceModel->countAbsences($user_id);

    return [
        'absences' => $absences,
        'total' => $total,
        'limit' => $limit,
        'page' => $page
    ];
}
public function totalAbsences()
{
    return $this->absenceModel->countAbsences($_SESSION['user_id']);
}

public function totalApprovedAbsences()
{
    return $this->absenceModel->countApprovedAbsences($_SESSION['user_id']);
}

public function totalPendingAbsences()
{
    return $this->absenceModel->countPendingAbsences($_SESSION['user_id']);
}

public function totalRefusedAbsences()
{
    return $this->absenceModel->countRefusedAbsences($_SESSION['user_id']);
}
//admin
public function totalAbsencesAdmin()
{
    return $this->absenceModel->totalAbsences();
}

public function totalApprovedAbsencesAdmin()
{
    return $this->absenceModel->totalApprovedAbsences();
}
public function totalPendingAbsencesAdmin()
{
    return $this->absenceModel->totalPendingAbsences();

}
public function totalRefusedAbsencesAdmin()
{
    return $this->absenceModel->totalRefusedAbsences();
}
//admine
public function indexAdmin()
{
    $limit = 5;

    $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;

    if ($page < 1) {
        $page = 1;
    }

    $offset = ($page - 1) * $limit;

    // Récupération des filtres
    $department = $_GET['department'] ?? null;
    $cohort = $_GET['cohort'] ?? null;
    $search = $_GET['search'] ?? null;

    // Liste des absences filtrées
    $absences = $this->absenceModel->getAllAdmin(
    $limit,
    $offset,
    $department,
    $cohort,
    $search
);

    // Total des absences (pour la pagination)
   $total = $this->absenceModel->countAllAbsencesFiltered(
    $department,
    $cohort,
    $search
);


    // Récupération des départements et cohortes
    $departments = $this->absenceModel->getDepartments();
    $cohorts = $this->absenceModel->getCohorts();

    return [
        'absences' => $absences,
        'departments' => $departments,
        'cohorts' => $cohorts,
        'total' => $total,
        'limit' => $limit,
        'page' => $page
    ];
}
//bouton
public function approve()
{
    if(isset($_GET['id']))
    {
        $absenceId = $_GET['id'];
        $this->absenceModel->approve($absenceId);
        addAudit($this->pdo, 'UPDATE', 'absences', $absenceId);

        $this->notifyStudentOfDecision($absenceId, 'approuve');

        header("Location:index.php?page=absence_admin");
        exit;
    }
}

public function refuse()
{
    if(isset($_GET['id']))
    {
        $absenceId = $_GET['id'];
        $this->absenceModel->refuse($absenceId);
        addAudit($this->pdo, 'UPDATE', 'absences', $absenceId);

        $this->notifyStudentOfDecision($absenceId, 'refuse');

        header("Location:index.php?page=absence_admin");
        exit;
    }
}

private function notifyStudentOfDecision($absenceId, $status)
{
    $absence = $this->absenceModel->getByIdWithStudent($absenceId);
    if (!$absence || empty($absence['email'])) {
        return;
    }

    $studentName = trim(($absence['firstname'] ?? '') . ' ' . ($absence['lastname'] ?? ''));
    $isApproved = $status === 'approuve';

    sendAbsenceNotificationMail(
        $absence['email'],
        $studentName ?: 'Etudiant',
        $isApproved ? 'Demande d absence approuvee' : 'Demande d absence refusee',
        $isApproved ? 'Votre demande est approuvee' : 'Votre demande est refusee',
        $isApproved
            ? 'Votre demande d absence a ete approuvee par un administrateur.'
            : 'Votre demande d absence a ete refusee par un administrateur.',
        $absence
    );
}

}
