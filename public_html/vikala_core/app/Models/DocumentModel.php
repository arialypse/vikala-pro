<?php
namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table            = 'documents';
    protected $primaryKey       = 'id';
    
    protected $allowedFields    = [
        'company_id',
        'user_id',
        'customer_id',
        'document_type',
        'document_number',
        'reference_number',
        'created_date',
        // กลุ่มฟิลด์ Snapshot
        'customer_name',
        'customer_address',
        'customer_tax_id',
        'customer_branch',
        // จำนวนเงิน (DECIMAL 12,2)
        'total_amount',
        'discount_amount',
        'vat_rate',
        'vat_amount',
        'wht_percentage',
        'wht_amount',
        'net_amount',
        'status'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; 

    /**
     * ฟังก์ชันรันเลขเอกสารใหม่ โดยมีกลไกป้องกัน Race Condition
     * ต้องถูกเรียกใช้ภายใต้ Database Transaction เสมอ ($this->db->transBegin())
     */
    public function generateNextDocumentNumber($companyId, $prefix)
    {
        $year = date('Y');
        $month = date('m');
        $searchPrefix = $prefix . $year . $month;
        
        // ใช้ Raw Query พร้อมคำสั่ง FOR UPDATE เพื่อล็อกแถวในจังหวะที่มีคนกดเซฟพร้อมกัน
        $sql = "SELECT document_number FROM {$this->table} 
                WHERE company_id = ? AND document_number LIKE ? 
                ORDER BY document_number DESC LIMIT 1 FOR UPDATE";
        
        $query = $this->db->query($sql, [$companyId, $searchPrefix . '%']);
        $lastDoc = $query->getRowArray();
        
        if ($lastDoc) {
            // ตัดเอา 4 หลักสุดท้ายมาแปลงเป็นตัวเลข และบวก 1
            $lastNumber = intval(substr($lastDoc['document_number'], -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // หากยังไม่มีบิลในเดือนนี้ ให้เริ่มที่ 0001
            $newNumber = '0001';
        }

        return $searchPrefix . $newNumber;
    }
}
