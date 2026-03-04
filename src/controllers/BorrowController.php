<?php
require_once __DIR__ . '/../models/BorrowRecord.php';
require_once __DIR__ . '/../models/Equipment.php';
require_once __DIR__ . '/../models/User.php';

class BorrowController
{
    private BorrowRecord $model;
    private Equipment    $equipmentModel;
    private User         $userModel;

    public function __construct()
    {
        $this->model          = new BorrowRecord();
        $this->equipmentModel = new Equipment();
        $this->userModel      = new User();
    }

    /** List all borrow records */
    public function index(): void
    {
        $records = $this->model->getAll();
        require __DIR__ . '/../views/borrows/index.php';
    }

    /** Show borrow form */
    public function create(): void
    {
        $users         = $this->userModel->getAll();
        $equipmentList = $this->equipmentModel->getAll();
        require __DIR__ . '/../views/borrows/create.php';
    }

    /** Handle borrow submission */
    public function store(): void
    {
        $qty = (int) ($_POST['quantity'] ?? 1);
        $eqId = (int) $_POST['equipment_id'];

        // Decrease available stock
        if (!$this->equipmentModel->decreaseStock($eqId, $qty)) {
            echo 'Not enough stock available.';
            return;
        }

        $this->model->create($_POST);
        header('Location: index.php?page=borrows');
        exit;
    }

    /** Handle return */
    public function returnItem(int $id): void
    {
        $record = $this->model->findById($id);
        if ($record && $this->model->returnItem($id)) {
            $this->equipmentModel->increaseStock(
                (int) $record['equipment_id'],
                (int) $record['quantity']
            );
        }
        header('Location: index.php?page=borrows');
        exit;
    }

    /** Handle delete */
    public function delete(int $id): void
    {
        $this->model->delete($id);
        header('Location: index.php?page=borrows');
        exit;
    }
}
