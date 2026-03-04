<?php
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /** Show login form */
    public function login(): void
    {
        // Already logged in? → go to dashboard
        if (!empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
        $error = '';
        require __DIR__ . '/../views/auth/login.php';
    }

    /** Authenticate user */
    public function authenticate(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน';
            require __DIR__ . '/../views/auth/login.php';
            return;
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง';
            require __DIR__ . '/../views/auth/login.php';
            return;
        }

        // Login success — store session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];

        // Flash message for dashboard
        $_SESSION['flash'] = [
            'level'   => 'success',
            'title'   => 'เข้าสู่ระบบสำเร็จ',
            'message' => 'ยินดีต้อนรับ, ' . $user['full_name'] . '!',
        ];

        header('Location: index.php');
        exit;
    }

    /** Logout */
    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
