<?php
namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * หน้าแสดงผลรายชื่อสินค้าทั้งหมด (ที่ยัง Active) ของบริษัทที่ล็อกอินอยู่
     */
    public function index()
    {
        $companyId = session()->get('active_company_id');
        if (!$companyId) {
            return redirect()->to('/dashboard')->with('error', 'กรุณาเลือกบริษัทก่อนจัดการสินค้า');
        }

        $data['products'] = $this->productModel->getActiveProducts($companyId);
        return view('products/index', $data);
    }

    /**
     * รับข้อมูลจากฟอร์มเพื่อบันทึกสินค้าใหม่
     */
    public function store()
    {
        $companyId = session()->get('active_company_id');
        if (!$companyId) {
            return redirect()->to('/dashboard')->with('error', 'กรุณาเลือกบริษัทก่อนจัดการสินค้า');
        }

        // กฎการตรวจสอบข้อมูลเบื้องต้น
        $rules = [
            'sku'        => 'required',
            'name'       => 'required',
            'unit_price' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // เตรียมข้อมูลบันทึกลง Database
        $data = [
            'company_id' => $companyId,
            'sku'        => $this->request->getPost('sku'),
            'name'       => $this->request->getPost('name'),
            'unit_price' => $this->request->getPost('unit_price'),
            'is_active'  => 1
        ];

        $this->productModel->insert($data);
        return redirect()->to('/products')->with('success', 'เพิ่มข้อมูลสินค้าใหม่สำเร็จ');
    }

    /**
     * รับข้อมูลเพื่ออัปเดตสินค้าเดิม 
     * (ข้อมูลเอกสารเก่าจะไม่เปลี่ยนตาม)
     */
    public function update($id)
    {
        $companyId = session()->get('active_company_id');
        if (!$companyId) {
            return redirect()->to('/dashboard')->with('error', 'กรุณาเลือกบริษัทก่อนจัดการสินค้า');
        }
        
        // เช็คก่อนว่าสินค้านี้เป็นของบริษัทนี้จริงๆ หรือไม่
        $product = $this->productModel->find($id);
        if (!$product || $product['company_id'] != $companyId) {
            return redirect()->to('/products')->with('error', 'ไม่พบสินค้า หรือคุณไม่มีสิทธิ์แก้ไขสินค้านี้');
        }

        $data = [
            'sku'        => $this->request->getPost('sku'),
            'name'       => $this->request->getPost('name'),
            'unit_price' => $this->request->getPost('unit_price')
        ];

        $this->productModel->update($id, $data);
        return redirect()->to('/products')->with('success', 'แก้ไขข้อมูลสินค้าสำเร็จ');
    }

    /**
     * ระบบ Soft Inactivation - ปิดการใช้งานแทนการลบ
     */
    public function disable($id)
    {
        $companyId = session()->get('active_company_id');
        if (!$companyId) {
            return redirect()->to('/dashboard')->with('error', 'กรุณาเลือกบริษัทก่อนจัดการสินค้า');
        }
        
        // เช็คสิทธิ์ความปลอดภัยก่อนลบ(ซ่อน)
        $product = $this->productModel->find($id);
        if (!$product || $product['company_id'] != $companyId) {
            return redirect()->to('/products')->with('error', 'ไม่พบสินค้า หรือคุณไม่มีสิทธิ์ลบสินค้านี้');
        }

        // ปรับสถานะ is_active เป็น 0 เพื่อซ่อนออกจากฟอร์ม
        $this->productModel->update($id, ['is_active' => 0]);
        return redirect()->to('/products')->with('success', 'ซ่อนข้อมูลสินค้าออกจากระบบแล้ว');
    }

    /**
     * API สำหรับช่องค้นหา Auto-complete ตอนออกบิล (ส่งค่ากลับเป็น JSON)
     */
    public function search()
    {
        $companyId = session()->get('active_company_id');
        if (!$companyId) {
            return $this->response->setJSON([]);
        }

        $keyword = $this->request->getGet('q');
        if (empty($keyword)) {
            return $this->response->setJSON([]);
        }

        $products = $this->productModel->searchProducts($companyId, $keyword);
        return $this->response->setJSON($products);
    }
}
