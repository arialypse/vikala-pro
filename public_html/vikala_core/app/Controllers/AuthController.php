<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\UserCompanyAccessModel;

class AuthController extends BaseController
{
    public function login()
    {
        // หากล็อกอินอยู่แล้ว ให้เตะไปหน้า Dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function processLogin()
    {
        $session = session();
        $userModel = new UserModel();
        $accessModel = new UserCompanyAccessModel();

        // รับค่าจากฟอร์ม
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // ค้นหาผู้ใช้งานในระบบ
        $user = $userModel->where('username', $username)->first();

        // ตรวจสอบความถูกต้องของรหัสผ่าน (ใช้ password_verify กับค่า Hash)
        if ($user && password_verify($password, $user['password_hash'])) {
            
            // 1. เตรียมข้อมูล Session พื้นฐาน
            $ses_data = [
                'user_id'    => $user['id'],
                'username'   => $user['username'],
                'fullname'   => $user['fullname'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ];

            // 2. จัดการสิทธิ์การเข้าถึงบริษัท (Multi-Company Tenant Mapping)
            if ($user['role'] === 'User') {
                // ดึงรายชื่อบริษัทที่ User คนนี้มีสิทธิ์เข้าถึง
                $accessList = $accessModel->where('user_id', $user['id'])->findAll();
                $companyIds = array_column($accessList, 'company_id');
                
                // หากไม่มีสิทธิ์บริษัทใดเลย ให้ตีกลับ
                if (empty($companyIds)) {
                    $session->setFlashdata('error', 'บัญชีของคุณยังไม่ได้ผูกสิทธิ์เข้าถึงบริษัทใดเลย กรุณาติดต่อผู้ดูแลระบบ');
                    return redirect()->to('/login');
                }
                
                $ses_data['allowed_companies'] = $companyIds;
                // ตั้งค่าเริ่มต้นให้ทำงานที่บริษัทแรกที่เจอ
                $ses_data['active_company_id'] = $companyIds[0]; 
            } else {
                // กรณีเป็น Admin ให้สิทธิ์ข้ามข้อจำกัด ('ALL')
                $ses_data['allowed_companies'] = 'ALL';
                $ses_data['active_company_id'] = null; // Admin สามารถสลับบริษัทอิสระจากเมนูส่วนกลาง
            }

            // บันทึก Log การเข้าสู่ระบบ (Audit Log)
            $this->logAction($user['id'], 'LOGIN', 'User logged in successfully');

            // เซ็ต Session และส่งเข้าหน้า Dashboard
            $session->set($ses_data);
            return redirect()->to('/dashboard');
            
        } else {
            // กรณีรหัสผิด
            $session->setFlashdata('error', 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง');
            $this->logAction($user['id'] ?? null, 'FAILED_LOGIN', "Attempt failed for username: {$username}");
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        $this->logAction($session->get('user_id'), 'LOGOUT', 'User logged out');
        $session->destroy();
        return redirect()->to('/login');
    }

    /**
     * ฟังก์ชันช่วยเหลือสำหรับบันทึก Audit Log (ทำงานสอดคล้องกับตาราง audit_logs)
     */
    private function logAction($userId, $action, $details)
    {
        $db = \Config\Database::connect();
        $db->table('audit_logs')->insert([
            'user_id'    => $userId,
            'action'     => $action,
            'details'    => $details,
            'ip_address' => $this->request->getIPAddress()
        ]);
    }
}