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
            return redirect()->to('/dashboard')->with('error', '????????????????????????????????');
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
            return redirect()->to('/dashboard')->with('error', '????????????????????????????????');
        }

        return view('documents/create');
    }

    public function store()
    {
        $companyId = session()->get('active_company_id');
        $userId = session()->get('user_id');

        if (!$companyId || !$userId) {
            return redirect()->to('/dashboard')->with('error', '????????????????');
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
            return redirect()->back()->withInput()->with('error', '?????????????????');
        }

        // 1. Snapshot ???????????? ? ????????????? (???????????????????????????????)
        $customerName   = $customer['name'];
        $customerAddress= $customer['address'];
        $customerTaxId  = $customer['tax_id'];
        $customerBranch = $customer['branch_code'];

        // 2. ????????????????????? Prefix (???????? companies)
        $db = \Config\Database::connect();
        $company = $db->table('companies')->where('id', $companyId)->get()->getRowArray();
        if (!$company) {
            return redirect()->back()->withInput()->with('error', '?????????????????');
        }

        $docType = $this->request->getPost('document_type');
        $prefix = 'IV';
        if ($docType == 'Invoice') $prefix = $company['invoice_prefix'];
        elseif ($docType == 'Receipt') $prefix = $company['receipt_prefix'];
        elseif ($docType == 'TaxInvoice') $prefix = $company['tax_invoice_prefix'];
        elseif ($docType == 'AbbrevInvoice') $prefix = $company['abbrev_prefix'];

        // 3. ????? Database Transaction ???????????????????
        $db->transException(true);
        try {
            $db->transBegin();

            // 4. ????????????????????? (Row Lock FOR UPDATE ???????????????????)
            $documentNumber = $this->documentModel->generateNextDocumentNumber($companyId, $prefix);

            // 5. ???????????? Header ??????
            $docData = [
                'company_id'       => $companyId,
                'user_id'          => $userId,
                'customer_id'      => $customerId,
                'document_type'    => $docType,
                'document_number'  => $documentNumber,
                'reference_number' => $this->request->getPost('reference_number'),
                'created_date'     => $this->request->getPost('created_date'),
                // Data Snapshot ??????????????
                'customer_name'    => $customerName,
                'customer_address' => $customerAddress,
                'customer_tax_id'  => $customerTaxId,
                'customer_branch'  => $customerBranch,
                // Money Data ???????????????????
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

            // 6. ???????????? Items
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
            return redirect()->to('/documents')->with('success', '??????????? ' . $documentNumber . ' ??????');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', '???????????????????????????????: ' . $e->getMessage());
        }
    }

    public function trash($id)
    {
        $companyId = session()->get('active_company_id');
        
        // ?????????????????????????????????????????????
        $document = $this->documentModel->find($id);
        if (!$document || $document['company_id'] != $companyId) {
            return redirect()->to('/documents')->with('error', '??????????? ?????????????????????');
        }

        // ??????????????? Trash ????????
        $this->documentModel->update($id, ['status' => 'Trash']);
        
        // ?????? Audit Log 
        $db = \Config\Database::connect();
        $db->table('audit_logs')->insert([
            'user_id'    => session()->get('user_id'),
            'action'     => 'TRASH_DOCUMENT',
            'details'    => 'Trashed document: ' . $document['document_number'],
            'ip_address' => $this->request->getIPAddress()
        ]);

        return redirect()->to('/documents')->with('success', '?????????????????????????????????');
    }
}
