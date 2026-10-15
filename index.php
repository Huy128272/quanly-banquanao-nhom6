<?php
//nhớ người đã đăng nhập
session_start();

require_once __DIR__ . '/app/config/helpers.php';
require_once __DIR__ . '/app/controller/DashboardController.php';
require_once __DIR__ . '/app/controller/ProductController.php';
require_once __DIR__ . '/app/controller/CategoryController.php';
require_once __DIR__ . '/app/controller/ProductDetailController.php';
require_once __DIR__ . '/app/controller/CartController.php';
require_once __DIR__ . '/app/controller/UserController.php';
require_once __DIR__ . '/app/controller/AuthController.php';

$modun  = $_GET['modun'] ?? "";
$action = $_GET['action'] ?? "";
$id     = $_GET['id'] ?? "";

// chưa đăng nhập thì quay lại trang đn
if (!isset($_SESSION['user']) && $modun !== 'Dangnhap') {
    header('Location: index.php?modun=Dangnhap');
    exit();
}

function chayAction($controller, $action, $id) {
    if ($action === 'Sua') {
        $controller->edit($id);
    } elseif ($action === 'Xoa') {
        $controller->delete($id);
    } elseif ($action === 'Them') {
        $controller->create();
    } else {
        $controller->index();
    }
}

switch ($modun) {
    case 'Dangnhap':
        (new AuthController())->login();
        break;
    case 'Dangxuat':
        (new AuthController())->logout();
        break;
    case 'Sanpham':
        chayAction(new ProductController(), $action, $id);
        break;
    case 'Danhmuc':
        chayAction(new CategoryController(), $action, $id);
        break;
    case 'Chitiet':
        chayAction(new ProductDetailController(), $action, $id);
        break;
    case 'Giohang':
        chayAction(new CartController(), $action, $id);
        break;
    case 'Nguoidung':
        chayAction(new UserController(), $action, $id);
        break;
    default:
        $controller = new DashboardController();
        $controller->index();
        break;
}
