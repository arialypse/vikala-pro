<?php
namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    /**
     * หน้าแสดงผลรายชื่อลูกค้าทั้งหมด (ที่ยัง Active)
     */
    public function index()
    {
        $data['customers'] = $this->customerModel->getActiveCustomers();
        return view('customers/index', $data);
    }

    /**
     * รับข้อมูลจากฟอร์มเพื่อบันทึกลูกค้าใหม่
     */
    public function store()
    {
        // กฎการตรวจสอบข้อมูลเบื้องต้น
        $rules = [
            'name'        => 'required|min_length[3]',
            'tax_id'      => 'required|min_length[13]',
            'address'     => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // เตรียมข้อมูลบันทึกลง Database
        $data = [
            'name'               => $this->request->getPost('name'),
            'address'            => $this->request->getPost('address'),
            'tax_id'             => $this->request->getPost('tax_id'),
            // บังคับให้มีรหัสสาขา 5 หลักเสมอตามกฎหมายสรรพากร ถ้าไม่กรอกให้เป็นสำนักงานใหญ่ 00000 
            'branch_code'        => $this->request->getPost('branch_code') ?: '00000', 
            'is_active'          => 1,
            'created_by_user_id' => session()->get('user_id')
        ];

        $this->customerModel->insert($data);
        return redirect()->to('/customers')->with('success', 'เพิ่มข้อมูลลูกค้าใหม่สำเร็จ');
    }

    /**
     * รับข้อมูลเพื่ออัปเดตลูกค้าเดิม 
     * (ข้อมูลเอกสารเก่าจะไม่เปลี่ยนตาม เพราะเรามีระบบ Data Snapshot ตอนออกบิล)
     */
    public function update($id)
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'address'     => $this->request->getPost('address'),
            'tax_id'      => $this->request->getPost('tax_id'),
            'branch_code' => $this->request->getPost('branch_code') ?: '00000'
        ];

        $this->customerModel->update($id, $data);
        return redirect()->to('/customers')->with('success', 'แก้ไขข้อมูลลูกค้าสำเร็จ');
    }

    /**
     * ระบบ Soft Inactivation - ปิดการใช้งานแทนการลบ
     */
    public function disable($id)
    {
        // ปรับสถานะ is_active เป็น 0 เพื่อซ่อนออกจากฟอร์ม
        $this->customerModel->update($id, ['is_active' => 0]);
        return redirect()->to('/customers')->with('success', 'ซ่อนข้อมูลลูกค้าออกจากระบบแล้ว');
    }

    /**
     * API สำหรับช่องค้นหา Auto-complete ตอนออกบิล (ส่งค่ากลับเป็น JSON)
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');
        if (empty($keyword)) {
            return $this->response->setJSON([]);
        }

        $customers = $this->customerModel->searchCustomers($keyword);
        return $this->response->setJSON($customers);
    }
}