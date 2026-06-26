<?php

require_once __DIR__ . '/../Models/Attendance.php';

class AttendanceController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Attendance($pdo);
    }

    // 🔹 Liste
    public function index()
    {
        $attendances = $this->model->getAll();
        $stats = $this->model->stats();

        require_once __DIR__ . '/../Views/attendance/index.php';
    }

    // 🔹 Check-in
    public function checkIn()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user_id = $_POST['user_id'];
            $status  = $_POST['status'] ?? 'present';

            $this->model->checkIn($user_id, $status);

            header("Location: attendance.php?success=checkin");
            exit;
        }
    }

    // 🔹 Check-out
    public function checkOut()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user_id = $_POST['user_id'];

            $this->model->checkOut($user_id);

            header("Location: attendance.php?success=checkout");
            exit;
        }
    }

    // 🔹 Voir un utilisateur
    public function show($user_id)
    {
        $attendances = $this->model->getByUser($user_id);

        require_once __DIR__ . '/../Views/attendance/show.php';
    }
}