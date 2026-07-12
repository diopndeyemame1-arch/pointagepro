<?php

require_once __DIR__ . '/../Models/Attendance.php';

class AttendanceController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Attendance($pdo);
    }

    public function adminData($page = 1, $limit = 9)
{
    $page = max(1, (int)$page);

    $offset = ($page - 1) * $limit;

    $date = date('Y-m-d');

    $users = $this->model->getAttendanceToday($date, $limit, $offset);

    return [
        'users' => $users,
        'total_students' => $this->model->countStudents(),
        'present' => $this->model->countPresentToday(),
        'late' => $this->model->countLateToday(),
    ];
}

 
    public function studentData($userId)
    {
        return [
            'history' => $this->model->getStudentHistory($userId),
        ];
    }

    public function pointerEntree($userId)
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');

        $existing = $this->model->checkToday($userId, $date);

        if ($existing) {
            return "déjà_pointé";
        }

        $status = ($time > "08:10:00") ? "retard" : "present";

        return $this->model->createEntry($userId, $date, $time, $status);
    }

   
    public function pointerSortie($userId)
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');

        return $this->model->updateExit($userId, $date, $time);
    }
}