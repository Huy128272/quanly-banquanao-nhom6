<?php
require_once __DIR__ . '/../model/User.php';

class AuthController {

    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        $loi = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userName = trim($_POST['user_name'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($userName === '' || $password === '') {
                $loi = 'Vui lòng nhập tên đăng nhập và mật khẩu!';
            } else {
                $user = $this->userModel->getByUserName($userName);
                
                if ($user && (password_verify($password, $user->password) || $password === $user->password)) {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id'        => $user->id,
                        'user_name' => $user->user_name,
                        'full_name' => $user->full_name,
                        'role'      => $user->role
                    ];
                    header('Location: index.php');
                    exit();
                }

                $loi = 'Sai tên đăng nhập hoặc mật khẩu!';
            }
        }

        require_once __DIR__ . '/../views/auth/LoginView.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?modun=Dangnhap');
        exit();
    }
}