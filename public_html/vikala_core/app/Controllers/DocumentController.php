<?php
namespace App\Controllers;

use App\Models\DocumentModel;
use App\Models\DocumentItemModel;
use App\Models\CustomerModel;

class DocumentController extends BaseController
{
    protected $documentModel;
    protected $documentItemModel;
    protected $customerModel;

    public function __construct()
    {
        $this->documentModel = new DocumentModel();
        $this->documentItemModel = new DocumentItemModel();
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        $companyId = session()->get('active_company_id');
        if (!$companyId) {
            return redirect()->to('/dashboard')->with('error', 'กรุณาเลือกบริษัทก่อนจัดการเอกสาร');
        }

        $data['documents'] = $this->documentModel->where('company_id', $companyId)
                                                ->orderBy('created_at', 'DESC')
                                                ->findAll();
        return view('documents/index', $data);
    }

    public function create()
    {
        $companyId = session()->get('active_company_id');
        if (!$companyId) {
            return redirect()->to('/dashboard')->with('error', 'กรุณาเลือกบริษัทก่อนจัดการเอกสาร');
        }

        return view('documents/create');
    }

    public function store()
    {
        $companyId = session()->get('active_company_id');
        $userId = session()->get('user_id');

        if (!$companyId || !$userId) {
            return redirect()->to('/dashboard')->with('error', 'เซสชันไม่สมบูรณ์');
        }

        // Validate basic rules
        $rules = [
            'customer_id'   => 'required|numeric',
            'document_type' => 'required|in_list[Invoice,Receipt,TaxInvoice,AbbrevInvoice]',
            'created_date'  => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $customerId = $this->request->getPost('customer_id');
        $customer = $this->customerModel->find($customerId);

        if (!$customer) {
            return redirect()->back()->withInput()->with('error', 'ไม่พบข้อมูลลูกค้า');
        }

        // 1. Snapshot ข้อมูลลูกค้า ณ เวลาที่ออกบิล (ล็อกข้อมูลไม่ให้เปลี่ยนย้อนหลัง)
        $customerName   = $customer['name'];
        $customerAddress= $customer['address'];
        $customerTaxId  = $customer['tax_id'];
        $customerBranch = $customer['branch_code'];

        // 2. ข้อมูลบริษัทสำหรับดึง Prefix (จากตาราง companies)
        $db = \Config\Database::connect();
        $company = $db->table('companies')->where('id', $companyId)->get()->getRowArray();
        if (!$company) {
            return redirect()->back()->withInput()->with('error', 'ไม่พบข้อมูลบริษัท');
        }

        $docType = $this->request->getPost('document_type');
        $prefix = 'IV';
        if ($docType == 'Invoice') $prefix = $company['invoice_prefix'];
        elseif ($docType == 'Receipt') $prefix = $company['receipt_prefix'];
        elseif ($docType == 'TaxInvoice') $prefix = $company['tax_invoice_prefix'];
        elseif ($docType == 'AbbrevInvoice') $prefix = $company['abbrev_prefix'];

        // 3. เริ่ม Database Transaction สำหรับล็อกการรันเลข
        $db->transException(true);
        try {
            $db->transBegin();

            // 4. สร้างเลขที่เอกสารใหม่ (Row Lock FOR UPDATE ถูกใช้งานในเมธอดนี้)
            $documentNumber = $this->documentModel->generateNextDocumentNumber($companyId, $prefix);

            // 5. บันทึกข้อมูล Header เอกสาร
            $docData = [
                'company_id'       => $companyId,
                'user_id'          => $userId,
                'customer_id'      => $customerId,
                'document_type'    => $docType,
                'document_number'  => $documentNumber,
                'reference_number' => $this->request->getPost('reference_number'),
                'created_date'     => $this->request->getPost('created_date'),
                // Data Snapshot ฝังลงบิลโดยตรง
                'customer_name'    => $customerName,
                'customer_address' => $customerAddress,
                'customer_tax_id'  => $customerTaxId,
                'customer_branch'  => $customerBranch,
                // Money Data รองรับถึงพันล้านบาท
                'total_amount'     => $this->request->getPost('total_amount') ?? 0,
                'discount_amount'  => $this->request->getPost('discount_amount') ?? 0,
                'vat_rate'         => $this->request->getPost('vat_rate') ?? 7.00,
                'vat_amount'       => $this->request->getPost('vat_amount') ?? 0,
                'wht_percentage'   => $this->request->getPost('wht_percentage') ?? 0,
                'wht_amount'       => $this->request->getPost('wht_amount') ?? 0,
                'net_amount'       => $this->request->getPost('net_amount') ?? 0,
                'status'           => 'Active'
            ];

            $this->documentModel->insert($docData);
            $documentId = $this->documentModel->getInsertID();

            // 6. บันทึกข้อมูล Items
            $items = $this->request->getPost('items');
            if (is_array($items) && !empty($items)) {
                $itemsToInsert = [];
                foreach ($items as $item) {
                    $itemsToInsert[] = [
                        'document_id'  => $documentId,
                        'product_name' => $item['product_name'],
                        'quantity'     => $item['quantity'],
                        'unit_price'   => $item['unit_price'],
                        'amount'       => $item['amount']
                    ];
                }
                $this->documentItemModel->insertBatch($itemsToInsert);
            }

            $db->transCommit();
            return redirect()->to('/documents')->with('success', 'สร้างเอกสาร ' . $documentNumber . ' สำเร็จ');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกเอกสาร: ' . $e->getMessage());
        }
    }

    public function trash($id)
    {
        $companyId = session()->get('active_company_id');
        
        // เช็คสิทธิ์และตรวจสอบว่ามีเอกสารนี้จริงหรือไม่
        $document = $this->documentModel->find($id);
        if (!$document || $document['company_id'] != $companyId) {
            return redirect()->to('/documents')->with('error', 'ไม่พบเอกสาร หรือไม่มีสิทธิ์ยกเลิก');
        }

        // อัปเดตสถานะเป็น Trash แทนการลบ
        $this->documentModel->update($id, ['status' => 'Trash']);
        
        // บันทึก Audit Log 
        $db = \Config\Database::connect();
        $db->table('audit_logs')->insert([
            'user_id'    => session()->get('user_id'),
            'action'     => 'TRASH_DOCUMENT',
            'details'    => 'Trashed document: ' . $document['document_number'],
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/documents')->with('success', 'ย้ายเอกสารเข้าถังขยะเรียบร้อยแล้ว');
    }

    public function print($id)
    {
        $companyId = session()->get('active_company_id');
        
        // ตรวจสอบสิทธิ์ว่าอยู่บริษัทเดียวกัน
        $document = $this->documentModel->find($id);
        if (!$document || $document['company_id'] != $companyId) {
            return redirect()->to('/documents')->with('error', 'ไม่พบเอกสาร หรือไม่มีสิทธิ์เข้าถึง');
        }

        $items = $this->documentItemModel->where('document_id', $id)->findAll();
        
        $db = \Config\Database::connect();
        $company = $db->table('companies')->where('id', $companyId)->get()->getRowArray();

        $data = [
            'document' => $document,
            'items'    => $items,
            'company'  => $company
        ];

        // โหลดหน้า print.php ลงในหน่วยความจำ
        $html = view('documents/print', $data);

        // สั่งรัน mPDF และแปลงโค้ด HTML เป็น PDF
        // หมายเหตุ: ต้องมั่นใจว่ารัน `composer require mpdf/mpdf` บนเซิร์ฟเวอร์แล้ว
        if (!class_exists('\Mpdf\Mpdf')) {
            return redirect()->to('/documents')->with('error', 'ไม่พบไลบรารี mPDF กรุณารัน composer require mpdf/mpdf หรือติดต่อผู้ดูแลระบบ');
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'default_font' => 'thsarabunnew'
        ]);

        $mpdf->WriteHTML($html);
        
        // ส่งออกไฟล์แบบ In-Memory (I = Inline in browser) ไม่เซฟไฟล์ลงดิสก์
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output($document['document_number'] . '.pdf', 'I');
        exit();
    }
}
