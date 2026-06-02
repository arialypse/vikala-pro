<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // ดึงข้อมูล Session
        $session = session();
        
        // ถ้าไม่มีสถานะ isLoggedIn ให้เตะกลับไปหน้า Login พร้อมข้อความแจ้งเตือน
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'กรุณาเข้าสู่ระบบก่อนเข้าใช้งานระบบ Vikala Pro');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ส่วนนี้เว้นว่างไว้ เพราะเราตรวจสอบแค่ก่อนเข้าถึง Controller (Before)
    }
}