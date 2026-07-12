<?php

require_once __DIR__ . '/../Models/Department.php';

class DepartmentController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Department($pdo);
    }

    public function index()
    {
        return [
            'departments' => $this->model->getDepartments(),
            'cohorts' => $this->model->getCohorts()
        ];
    }
  public function showCohorts($id)
{
    $rows = $this->model->getDepartmentWithCohorts($id);

    if (!$rows) {
        return null;
    }

    return [
        'department' => $rows[0]['department_name'],
        'cohorts'    => $rows
    ];
}
public function store()
{
    $this->model->create(
        $_POST['name'],
        $_POST['description']
    );

    header("Location: index.php?page=departments");
    exit;
}
}