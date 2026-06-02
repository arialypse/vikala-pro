<?php
namespace App\Models;

use CodeIgniter\Model;

class DocumentItemModel extends Model
{
    protected $table            = 'document_items';
    protected $primaryKey       = 'id';
    
    protected $allowedFields    = [
        'document_id',
        'product_name',
        'quantity',
        'unit_price',
        'amount'
    ];

    // ตารางนี้ไม่มี created_at, updated_at ตาม Schema ที่ออกแบบไว้
    protected $useTimestamps    = false;
}
