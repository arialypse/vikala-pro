<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// หน้าแรกสุดของเว็บไซต์ (Default Route) ให้เด้งไปหน้า Login อัตโนมัติ
$routes->get('/', 'AuthController::login');

// --- บล็อกระบบ Authentication (เส้นทางสาธารณะ ไม่ต้องล็อกอิน) ---
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');

// --- บล็อกระบบปฏิบัติงานภายใน (ทุกเส้นทางต้องผ่านด่านตรวจ AuthFilter) ---
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    
    // หน้าแรกหลังล็อกอินสำเร็จ (Dashboard Ecosystem)
    $routes->get('dashboard', 'DashboardController::index');
    
    // --- Phase 2: ระบบบริหารจัดการลูกค้าส่วนกลาง (Shared Global Customers) ---
    // หน้าหลักแสดงรายการลูกค้า
    $routes->get('customers', 'CustomerController::index');
    
    // บันทึกข้อมูลลูกค้าใหม่
    $routes->post('customers/store', 'CustomerController::store');
    
    // บันทึกการแก้ไขข้อมูลลูกค้าเก่า (อ้างอิงตาม ID เลขระบุตัวตน)
    $routes->post('customers/update/(:num)', 'CustomerController::update/$1');
    
    // ระบบ Soft Delete เปลี่ยนสถานะปิดการใช้งาน (Disable) แทนการลบข้อมูลจริง
    $routes->get('customers/disable/(:num)', 'CustomerController::disable/$1');
    
    // Endpoint API สำหรับดึงข้อมูลลูกค้าไปทำระบบพิมพ์ค้นหาอัตโนมัติ (Auto-complete) ตอนออกบิล
    $routes->get('api/customers/search', 'CustomerController::search');
    
});