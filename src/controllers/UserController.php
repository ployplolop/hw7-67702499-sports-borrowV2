<?php
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /** List all users */
    public function index(): void
    {
        $users = $this->model->getAll();
        require __DIR__ . '/../views/users/index.php';
    }

    /** Show create form */
    public function create(): void
    {
        require __DIR__ . '/../views/users/create.php';
    }

    /** Handle store */
    public function store(): void
    {
        $this->model->create($_POST);
        header('Location: index.php?page=users');
        exit;
    }

    /** Show edit form */
    public function edit(int $id): void
    {
        $user = $this->model->findById($id);
        require __DIR__ . '/../views/users/edit.php';
    }

    /** Handle update */
    public function update(int $id): void
    {
        $this->model->update($id, $_POST);
        header('Location: index.php?page=users');
        exit;
    }

    /** Handle delete */
    public function delete(int $id): void
    {
        $this->model->delete($id);
        header('Location: index.php?page=users');
        exit;
    }
}
