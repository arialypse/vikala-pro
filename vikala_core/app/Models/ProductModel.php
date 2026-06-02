<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    
    // กำหนดฟิลด์ที่อนุญาตให้ Insert/Update ได้
    protected $allowedFields    = [
        'company_id',
        'sku',
        'name',
        'unit_price',
        'is_active'
    ];

    // โครงสร้าง Database ของเราไม่มี updated_at เราจึงเปิดแค่ created_at
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; 

    /**
     * ดึงรายชื่อสินค้าที่ยังเปิดใช้งานอยู่ของบริษัทที่ระบุเท่านั้น
     */
    public function getActiveProducts($companyId)
    {
        return $this->where('company_id', $companyId)
                    ->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * ฟังก์ชันค้นหาสินค้า (รองรับการค้นหาด้วย ชื่อ หรือ SKU)
     * นำไปใช้ทำระบบ Auto-complete ตอนพิมพ์ออกเอกสาร โดยระบุ company_id เพื่อแยกข้อมูล
     */
    public function searchProducts($companyId, $keyword)
    {
        return $this->where('company_id', $companyId)
                    ->where('is_active', 1)
                    ->groupStart()
                        ->like('name', $keyword)
                        ->orLike('sku', $keyword)
                    ->groupEnd()
                    ->orderBy('name', 'ASC')
                    ->findAll(20);
    }
}
