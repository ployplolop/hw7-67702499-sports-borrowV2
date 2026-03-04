<?php
require_once __DIR__ . '/../models/Equipment.php';

class EquipmentController
{
    private Equipment $model;

    public function __construct()
    {
        $this->model = new Equipment();
    }

    /** List all equipment */
    public function index(): void
    {
        $equipmentList = $this->model->getAll();
        require __DIR__ . '/../views/equipment/index.php';
    }

    /** Show create form */
    public function create(): void
    {
        require __DIR__ . '/../views/equipment/create.php';
    }

    /** Handle store */
    public function store(): void
    {
        $this->model->create($_POST);
        header('Location: index.php?page=equipment');
        exit;
    }

    /** Show edit form */
    public function edit(int $id): void
    {
        $equipment = $this->model->findById($id);
        require __DIR__ . '/../views/equipment/edit.php';
    }

    /** Handle update */
    public function update(int $id): void
    {
        $this->model->update($id, $_POST);
        header('Location: index.php?page=equipment');
        exit;
    }

    /** Handle delete */
    public function delete(int $id): void
    {
        $this->model->delete($id);
        header('Location: index.php?page=equipment');
        exit;
    }
}
