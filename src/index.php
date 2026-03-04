<?php
/**
 * Front Controller — routes all requests to the appropriate controller/action.
 *
 * URL pattern:  index.php?page={module}&action={action}&id={id}
 *   page   = login | users | equipment | borrows   (default: dashboard)
 *   action = index | create | store | edit | update | delete | return | authenticate | logout
 */

session_start();

$page   = $_GET['page']   ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;

/* ---- Login / Logout (no auth required) ---- */
if ($page === 'login') {
    require_once __DIR__ . '/controllers/AuthController.php';
    $ctrl = new AuthController();
    match ($action) {
        'authenticate' => $ctrl->authenticate(),
        'logout'       => $ctrl->logout(),
        default        => $ctrl->login(),
    };
    exit;
}

/* ---- Auth guard: redirect to login if not logged in ---- */
if (empty($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

switch ($page) {
    /* ---- Dashboard ---- */
    case 'dashboard':
        require __DIR__ . '/views/dashboard.php';
        break;

    /* ---- Calendar ---- */
    case 'calendar':
        require __DIR__ . '/views/calendar.php';
        break;

    /* ---- Users ---- */
    case 'users':
        require_once __DIR__ . '/controllers/UserController.php';
        $ctrl = new UserController();
        match ($action) {
            'create' => $ctrl->create(),
            'store'  => $ctrl->store(),
            'edit'   => $ctrl->edit($id),
            'update' => $ctrl->update($id),
            'delete' => $ctrl->delete($id),
            default  => $ctrl->index(),
        };
        break;

    /* ---- Equipment ---- */
    case 'equipment':
        require_once __DIR__ . '/controllers/EquipmentController.php';
        $ctrl = new EquipmentController();
        match ($action) {
            'create' => $ctrl->create(),
            'store'  => $ctrl->store(),
            'edit'   => $ctrl->edit($id),
            'update' => $ctrl->update($id),
            'delete' => $ctrl->delete($id),
            default  => $ctrl->index(),
        };
        break;

    /* ---- Borrow Records ---- */
    case 'borrows':
        require_once __DIR__ . '/controllers/BorrowController.php';
        $ctrl = new BorrowController();
        match ($action) {
            'create' => $ctrl->create(),
            'store'  => $ctrl->store(),
            'return' => $ctrl->returnItem($id),
            'delete' => $ctrl->delete($id),
            default  => $ctrl->index(),
        };
        break;

    /* ---- Fallback ---- */
    default:
        require __DIR__ . '/views/dashboard.php';
        break;
}
