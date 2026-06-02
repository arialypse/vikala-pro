SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- 1. ตารางเก็บข้อมูลบริษัทผู้ใช้งานระบบ
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `tax_id` varchar(20) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `is_vat_registered` tinyint(1) NOT NULL DEFAULT 1,
  `invoice_prefix` varchar(10) NOT NULL DEFAULT 'IV',
  `receipt_prefix` varchar(10) NOT NULL DEFAULT 'RE',
  `tax_invoice_prefix` varchar(10) NOT NULL DEFAULT 'TX',  
  `abbrev_prefix` varchar(10) NOT NULL DEFAULT 'ABB',     
  `show_remarks` tinyint(1) NOT NULL DEFAULT 1,
  `show_signatures` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 2. ตารางเก็บข้อมูลบัญชีผู้ใช้งาน
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `reset_token` varchar(255) DEFAULT NULL,                 
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- แทรกข้อมูลบัญชี Admin เริ่มต้น (Username: admin / Password: password123)
INSERT INTO `users` (`username`, `password_hash`, `fullname`, `email`, `role`) VALUES
('admin', '$2y$10$xIq.4xI2JtB3L99O3J8C.u9hG7k34G62.8T2G77b6Y162x6G94zE.', 'System Administrator', 'admin@vikalapro.com', 'Admin');

-- 3. ตารางสำหรับผูกสิทธิ์สัญญาระหว่างผู้ใช้งานกับสิทธิ์เข้าถึงแต่ละบริษัท
CREATE TABLE `user_company_access` (
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`company_id`),
  CONSTRAINT `fk_access_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_access_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 4. ฐานข้อมูลลูกค้ารวมศูนย์ (Shared Global Database)
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `tax_id` varchar(20) NOT NULL,
  `branch_code` varchar(5) NOT NULL DEFAULT '00000',      
  `is_active` tinyint(1) NOT NULL DEFAULT 1,               
  `created_by_user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_customer_search` (`name`,`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 5. ฐานข้อมูลสินค้าและบริการ (Isolated แยกรายบริษัท)
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,               
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_product_company` (`company_id`),
  CONSTRAINT `fk_product_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 6. ตารางหลักเก็บหัวเอกสารทางบัญชี (DECIMAL(12,2) รองรับยอดเงินหลักพันล้าน)
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `document_type` enum('Invoice','Receipt','TaxInvoice','AbbrevInvoice') NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,            
  `created_date` date NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_tax_id` varchar(20) NOT NULL,
  `customer_branch` varchar(5) NOT NULL DEFAULT '00000',
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `vat_rate` decimal(5,2) NOT NULL DEFAULT 7.00,         
  `vat_amount` decimal(12,2) NOT NULL DEFAULT 0.00,       
  `wht_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `wht_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('Active','Trash') NOT NULL DEFAULT 'Active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_doc_num` (`company_id`,`document_number`),
  CONSTRAINT `fk_doc_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `fk_doc_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 7. ตารางเก็บรายการสินค้าที่ระบุภายในแต่ละเอกสาร
CREATE TABLE `document_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_item_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- 8. ตารางสำหรับบันทึกประวัติความปลอดภัยและการปฏิบัติงาน (Audit Log)
CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

COMMIT;