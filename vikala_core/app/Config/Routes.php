<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// หน้าแรกสุด (Default) ถ้าเข้าเว็บมาตรงๆ ให้เด้งไปหน้า Login
$routes->get('/', 'AuthController::login');

// กลุ่ม Routes สาธารณะ (ไม่ต้องล็อกอิน)
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');

// กลุ่ม Routes ภายใน (ต้องผ่านด่านตรวจ AuthFilter ก่อน)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    
    // หน้า Dashboard (เดี๋ยวเราจะสร้าง Controller ตัวนี้ในอนาคต)
    $routes->get('dashboard', 'DashboardController::index');
    
    // เส้นทางสำหรับ Phase 2: ระบบลูกค้า (Customers)
    $routes->get('customers', 'CustomerController::index');
    
});