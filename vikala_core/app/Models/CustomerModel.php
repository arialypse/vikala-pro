<?php
namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    
    // กำหนดฟิลด์ที่อนุญาตให้ Insert/Update ได้
    protected $allowedFields    = [
        'name', 
        'address', 
        'tax_id', 
        'branch_code', 
        'is_active', 
        'created_by_user_id'
    ];

    // โครงสร้าง Database ของเราไม่มี updated_at เราจึงเปิดแค่ created_at
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; 

    /**
     * ดึงรายชื่อลูกค้าที่ยังเปิดใช้งานอยู่เท่านั้น (สำหรับแสดงใน Dropdown ตอนออกบิล)
     */
    public function getActiveCustomers()
    {
        return $this->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * ฟังก์ชันค้นหาลูกค้า (รองรับการค้นหาด้วย ชื่อ หรือ เลขผู้เสียภาษี)
     * นำไปใช้ทำระบบ Auto-complete ตอนพิมพ์ออกเอกสาร
     */
    public function searchCustomers($keyword)
    {
        return $this->where('is_active', 1)
                    ->groupStart()
                        ->like('name', $keyword)
                        ->orLike('tax_id', $keyword)
                    ->groupEnd()
                    ->orderBy('name', 'ASC')
                    ->findAll(20); // Limit ไว้ที่ 20 รายการเพื่อไม่ให้โหลดหนักเกินไป
    }
}