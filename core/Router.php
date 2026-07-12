<?php

class Router
{
    public static function route($page, $pdo)
    {
        switch ($page) {

            // ======================
            // Dashboard
            // ======================
            case 'admin':
                require_once __DIR__ . '/../app/Views/dashboard/admin.php';
                break;

            case 'etudiant':
                require_once __DIR__ . '/../app/Views/dashboard/etudiant.php';
                break;
            case 'pointage_entree':
    require_once __DIR__ . '/../app/Controllers/PointageController.php';
    $controller = new PointageController($pdo);
    $controller->entree();
    break;

case 'pointage_sortie':
    require_once __DIR__ . '/../app/Controllers/PointageController.php';
    $controller = new PointageController($pdo);
    $controller->sortie();
    break;
            // ======================
            // Utilisateurs
            // ======================
            case 'users':

                require_once __DIR__ . '/../app/Controllers/UserController.php';
            
                $controller = new UserController($pdo);
            
                $users = $controller->index();
            
                require_once __DIR__ . '/../app/Views/users/utilisateur.php';
            
            break;

            case 'activate':
                require_once __DIR__ . '/../app/Views/users/activate.php';
                break;
            case 'store_user':

    require_once __DIR__ . '/../app/Controllers/UserController.php';

    $controller = new UserController($pdo);

    $controller->store();

    header("Location: index.php?page=users");
    exit;
    case 'delete_user':

    require_once __DIR__ . '/../app/Controllers/UserController.php';

    $controller = new UserController($pdo);

    $controller->delete($_GET['id']);

    header("Location: index.php?page=users");
    exit;
    case 'toggle_user_status':

    require_once __DIR__ . '/../app/Controllers/UserController.php';

    $controller = new UserController($pdo);

    $controller->toggleStatus($_GET['id'] ?? null);

    header("Location: index.php?page=users");
    exit;
    case 'update_user':

    require_once __DIR__ . '/../app/Controllers/UserController.php';

    $controller = new UserController($pdo);

    $controller->update($_POST);

    header("Location: index.php?page=users");
    exit;
    case 'update_settings':

    require_once __DIR__ . '/../app/Controllers/SettingsController.php';

    $controller = new SettingsController($pdo);

    $controller->update();

    break;
    case 'import_users':

    require_once __DIR__ . '/../app/Controllers/UserController.php';

    $controller = new UserController($pdo);

    $controller->import();

    header("Location: index.php?page=users");
    exit;

break;
case 'save_password':

    require_once __DIR__ . '/../app/Views/users/save_password.php';

    break;
    // ====================
    // Audi logs
    // ====================
    case 'audit_logs':
    require_once __DIR__ . '/../app/Controllers/AuditLogController.php';
    $controller = new AuditLogController($pdo);
    $auditData = $controller->index($_GET['p'] ?? 1, 10);
    $logs = $auditData['logs'];
    $totalLogs = $auditData['total'];
    $totalPages = $auditData['totalPages'];
    $currentPage = $auditData['currentPage'];
    $auditStats = $auditData['stats'];
    require_once __DIR__ . '/../app/Views/audit/audit_logs.php';
    break;

            // ======================
            // Auth
            // ======================
            case 'login':
                require_once __DIR__ . '/../app/Views/auth/login.php';
                break;
            case 'trait-login':
            case 'trait_login':
                require_once __DIR__ . '/../app/Views/auth/trait-login.php';
                break;
            case 'mot-de-passe-oublie':
                require_once __DIR__ . '/../app/Views/auth/forgot_password.php';
                break;
            case 'send_reset_link':
                require_once __DIR__ . '/../app/Views/auth/send_reset_link.php';
                break;
            case 'reset-password':
                require_once __DIR__ . '/../app/Views/auth/reset_password.php';
                break;

            case 'logout':
                require_once __DIR__ . '/../app/Views/auth/logout.php';
                break;

            case 'departments':

    require_once __DIR__ . '/../app/Controllers/DepartmentController.php';

    $controller = new DepartmentController($pdo);

    $data = $controller->index();

    $departments = $data['departments'];
    $cohorts = $data['cohorts'];

    require_once __DIR__ . '/../app/Views/departments/department.php';

break;

case 'profil':

    require_once __DIR__ . '/../app/Views/settings/profil.php';

break;

            // ======================
            // Absences
            // ======================
            case 'absence':

                require_once __DIR__ . '/../app/Controllers/AbsController.php';

                $controller = new AbsController($pdo);

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $controller->store();
                }

                $data = $controller->index();

                $absences = $data['absences'];
                $total = $data['total'];
                $limit = $data['limit'];
               $currentPage = $data['page'];

                $totalAbsences = $controller->totalAbsences();
                $totalApprovedAbsences = $controller->totalApprovedAbsences();
                $totalPendingAbsences = $controller->totalPendingAbsences();
                $totalRefusedAbsences = $controller->totalRefusedAbsences();

               
                require_once __DIR__ . '/../app/Views/absence/absence_etu.php';

                break;
                //bouton
                case 'approve_absence':

                    require_once __DIR__.'/../app/Controllers/AbsController.php';
                
                    $controller = new AbsController($pdo);
                
                    $controller->approve();
                
                break;


case 'refuse_absence':

    require_once __DIR__.'/../app/Controllers/AbsController.php';

    $controller = new AbsController($pdo);

    $controller->refuse();

break;

            case 'absence_admin':

    require_once __DIR__ . '/../app/Controllers/AbsController.php';

    $controller = new AbsController($pdo);

    // Données
    $data = $controller->indexAdmin();

    $absences = $data['absences'];

    // AJOUTER CES DEUX LIGNES
    $departments = $data['departments'];
    $cohorts = $data['cohorts'];

    $total = $data['total'];
    $limit = $data['limit'];
    $currentPage = $data['page'];

    // KPI
    $totalAbsencesAdmin = $controller->totalAbsencesAdmin();
    $totalApprovedAbsencesAdmin = $controller->totalApprovedAbsencesAdmin();
    $totalPendingAbsencesAdmin = $controller->totalPendingAbsencesAdmin();
    $totalRefusedAbsencesAdmin = $controller->totalRefusedAbsencesAdmin();

    require_once __DIR__ . '/../app/Views/absence/absence.php';

break;
            // ======================
            // Présences
            // ======================
            case 'presence':
                require_once __DIR__ . '/../app/Views/attendance/presence_etu.php';
                break;

            case 'presence_admin':

    require_once __DIR__ . '/../app/Controllers/AttendanceController.php';

    $controller = new AttendanceController($pdo);

    $p = isset($_GET['p']) ? (int)$_GET['p'] : 1;

    $data = $controller->adminData($p, 6);

    require_once __DIR__ . '/../app/Views/attendance/presence.php';

    break;

            // ======================
            // QR CODE
            // ======================
     case 'qr_code':

    require_once __DIR__ . '/../app/Controllers/QrCodeController.php';

    $controller = new QrCodeController($pdo);

    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_GET['action'])
        && $_GET['action'] === 'generate'
    ) {
        $controller->generate();
    }

    $data = $controller->index();
    $qrCode = $data['qrCode'];

    require_once __DIR__ . '/../app/Views/qrCode/qr_codes.php';

    break;
    case 'scan_qr':
    if (!ob_get_level()) {
        ob_start();
    }
    require_once __DIR__ . '/../app/Controllers/QrCodeController.php';
    $controller = new QrCodeController($pdo);
    $controller->scan();
    if (ob_get_level()) {
        @ob_end_flush();
    }
    break;

    case 'scan_admin_qr':
    if (!ob_get_level()) {
        ob_start();
    }
    require_once __DIR__ . '/../app/Controllers/QrCodeEtuController.php';
    $controller = new QrCodeEtuController($pdo);
    $controller->scanAdminQr();
    break;

case 'list_presence':
    require_once __DIR__ . '/../app/Controllers/QrCodeController.php';
    $controller = new QrCodeController($pdo);
    $controller->listPresence();
    break;

           case 'qr_code_etu':

    require_once __DIR__ . '/../app/Controllers/QrCodeEtuController.php';

    $controller = new QrCodeEtuController($pdo);

    $data = $controller->index();

    $etudiant = $data['etudiant'];
    $qrCode = $data['qrCode'];

    require_once __DIR__ . '/../app/Views/qrCode/qrcode_etu.php';

    break;
   case 'qr_code_etu':

    require_once __DIR__ . '/../app/Controllers/QrCodeEtuController.php';

    $controller = new QrCodeEtuController($pdo);

    $data = $controller->index();

    $etudiant = $data['etudiant'];
    $qrCode   = $data['qrCode'];

    require_once __DIR__ . '/../app/Views/qrCode/qrcode_etu.php';

break;

            


            // ======================
            // Jours fériés
            // ======================
  case 'holiday':

    require_once __DIR__ . '/../app/Controllers/PublicHolidayController.php';
    $controller = new PublicHolidayController($pdo);

    // CREATE
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->store();
    }

    $data = $controller->index($_GET['p'] ?? 1, 5, $_GET['month'] ?? null);

    $holidays   = $data['holidays'];
    $total      = $data['total'];
    $thisMonth  = $data['thisMonth'];
    $next       = $data['next'];
    $remaining  = $data['remaining'];
    $page       = $data['page'];
    $selectedMonth = $data['month'];
    $totalPages = $data['totalPages'];

    require_once __DIR__ . '/../app/Views/publicHoliday/publicHoliday.php';
    break;
    case 'delete_holiday':

    require_once __DIR__ . '/../app/Controllers/PublicHolidayController.php';

    $controller = new PublicHolidayController($pdo);
    $controller->delete($_GET['id']);

    $monthQuery = isset($_GET['month']) && preg_match('/^(0?[1-9]|1[0-2])$/', $_GET['month'])
        ? '&month=' . urlencode($_GET['month'])
        : '';
    header("Location: index.php?page=holiday" . $monthQuery);
    exit;
    case 'update_holiday':

    require_once __DIR__ . '/../app/Controllers/PublicHolidayController.php';

    $controller = new PublicHolidayController($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->update();
    }

    $monthQuery = isset($_GET['month']) && preg_match('/^(0?[1-9]|1[0-2])$/', $_GET['month'])
        ? '&month=' . urlencode($_GET['month'])
        : '';
    header("Location: index.php?page=holiday" . $monthQuery);
    exit;

break;
case 'store_holiday':

    require_once __DIR__ . '/../app/Controllers/PublicHolidayController.php';

    $controller = new PublicHolidayController($pdo);

    $controller->store();

    header("Location: index.php?page=holiday");
    exit;

break;

// LISTE
$data = $controller->index($_GET['p'] ?? 1) ?? [];

$holidays   = $data['holidays'] ?? [];
$total      = $data['total'] ?? 0;
$thisMonth  = $data['thisMonth'] ?? 0;
$next       = $data['next'] ?? null;
$remaining  = $data['remaining'] ?? 0;
$limit      = $data['limit'] ?? 5;
$page       = $data['page'] ?? 1;

$totalPages = ($limit > 0) ? ceil($total / $limit) : 1;

require_once __DIR__ . '/../app/Views/publicHoliday/publicHoliday.php';

break;
case 'department_cohorts':

    require_once __DIR__ . '/../app/Controllers/DepartmentController.php';

    $controller = new DepartmentController($pdo);

    $id = $_GET['id'] ?? 0;

    $data = $controller->showCohorts($id);

    require_once __DIR__ . '/../app/Views/departments/department_cohorts.php';

break;
case 'update_cohort':

    require_once __DIR__ . '/../app/Controllers/CohortController.php';

    $controller = new CohortController($pdo);

    $controller->update();

    break;

case 'delete_cohort':

    require_once __DIR__ . '/../app/Controllers/CohortController.php';

    $controller = new CohortController($pdo);

    $controller->delete();

    break;
    case 'store_cohort':

    require_once __DIR__.'/../app/Controllers/CohortController.php';

    $controller = new CohortController($pdo);

    $controller->store();

    break;
    case 'store_department':

    require_once __DIR__ . '/../app/Controllers/DepartmentController.php';

    $controller = new DepartmentController($pdo);

    $controller->store();

    break;
            // ======================
            // Rapports
            // ======================
            case 'reports':
                require_once __DIR__ . '/../app/Controllers/ReportController.php';
                $controller = new ReportController($pdo);
                $reportData = $controller->index();
                require_once __DIR__ . '/../app/Views/reports/rapport.php';
                break;

            // ======================
            // Paramètres
            // ======================
            case 'settings':
                require_once __DIR__ . '/../app/Views/settings/parametre.php';
                break;

            default:
                http_response_code(404);
                echo "<h2>404 - Page introuvable</h2>";
                break;
        }
    }
}
