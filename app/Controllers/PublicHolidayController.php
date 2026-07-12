<?php

require_once __DIR__ . '/../Models/PublicHoliday.php';

class PublicHolidayController
{
    private $model;

    private $pdo;

public function __construct($pdo)
{
    $this->pdo = $pdo;
    $this->model = new PublicHoliday($pdo);
}

    // LISTE
 public function index($page = 1, $limit = 5, $month = null)
{
    $page = max(1, (int) $page);
    $month = (is_string($month) && preg_match('/^(0?[1-9]|1[0-2])$/', $month)) ? (int) $month : null;
    $offset = ($page - 1) * $limit;

    $holidays = $this->model->getPaginated($limit, $offset, $month);

    $total = $this->model->countAll($month);
    $thisMonth = $this->model->countThisMonth();
    $next = $this->model->getNextHoliday();
    $remaining = $this->model->countRemaining();

    return [
        'holidays' => $holidays,
        'total' => $total,
        'thisMonth' => $thisMonth,
        'next' => $next,
        'remaining' => $remaining,
        'page' => $page,
        'month' => $month,
        'totalPages' => ceil($total / $limit)
    ];
}
public function update()
{
    $id = $_POST['id'] ?? ($_GET['id'] ?? null);
    $name = $_POST['name'] ?? '';
    $date = $_POST['holiday_date'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!$id || !$name || !$date || !$type) {
        die("Champs obligatoires manquants");
    }

    $this->model->update($id, $name, $date, $type, $description);
}
public function delete($id)
{
    $this->model->delete($id);
}

    // CREATE
  public function store()
{
    $holiday_name = $_POST['name'] ?? '';
    $holiday_date = $_POST['holiday_date'] ?? '';
    $holiday_type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!$holiday_name || !$holiday_date || !$holiday_type) {
        die("Champs obligatoires manquants");
    }

    $today = date('Y-m-d');

    $status = ($holiday_date < $today) ? 'passe' : 'avenir';

    $stmt = $this->pdo->prepare("
        INSERT INTO public_holidays
        (holiday_name, holiday_date, holiday_type, description, status)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $holiday_name,
        $holiday_date,
        $holiday_type,
        $description,
        $status
    ]);

   header("Location: index.php?page=holiday");
    exit;
}
}
