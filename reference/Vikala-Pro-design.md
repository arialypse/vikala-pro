# Vikala Pro - System Architecture, Planning, and Design Document (v4.00)

เอกสารนี้รวบรวมข้อกำหนด การวางแผนสถาปัตยกรรม และการออกแบบโครงสร้างทั้งหมดสำหรับระบบแอปพลิเคชันออกใบเสร็จรับเงินและใบกำกับภาษี **"Vikala Pro"** เพื่อใช้เป็นพิมพ์เขียวสำหรับการพัฒนาและดูแลระบบในอนาคต

---

## 1. ข้อมูลสภาพแวดล้อมระบบและข้อจำกัด (Server Environment & Constraints)

ระบบได้รับการตั้งค่าเพื่อทำงานร่วมกับโครงสร้าง Shared Hosting ที่ระบุอย่างเจาะจง ดังนี้:
* **Hosting Control Panel:** cPanel เวอร์ชัน 134.0.30 (สิทธิ์เข้าถึงผ่าน FTP Access)
* **Web Server:** LiteSpeed Web Server
* **PHP Version:** PHP 8.2.31 (PHP SAPI: litespeed, รองรับค่า 64bit)
* **Database Management:** phpMyAdmin บนฐานข้อมูล 10.6.27-MariaDB (Client version: mysqlnd 8.2.31)
* **PHP Memory & Limits:** `memory_limit`: 2G, `upload_max_filesize`: 512M, `max_execution_time`: 60 วินาที
* **ข้อจำกัดที่ต้องระวัง (Disk Space Limit):** เนื่องจากการใช้งานฮาร์ดดิสก์ของเซิร์ฟเวอร์หลัก (Disk /) อยู่ที่ **89%** ระบบ Vikala Pro จะ**ไม่มีการจัดเก็บไฟล์เอกสาร PDF สำเร็จรูปไว้บนดิสก์ของเซิร์ฟเวอร์** แต่จะใช้สถาปัตยกรรมสร้างไฟล์ PDF แบบ Real-time เมื่อผู้ใช้กดดาวน์โหลดหรือสั่งพิมพ์เท่านั้น เพื่อประหยัดพื้นที่จัดเก็บข้อมูล

---

## 2. สถาปัตยกรรมและเครื่องมือที่เลือกใช้ (Tech Stack & Architecture)

* **PHP Framework:** เลือกใช้ **CodeIgniter 4 (CI4)**
    * *เหตุผล:* ประสิทธิภาพความเร็วสูง น้ำหนักเบา มีการจัดการความปลอดภัยในตัว (CSRF, SQL Injection) และติดตั้งง่ายบน Shared Hosting ผ่าน FTP โดยไม่ต้องใช้สิทธิ์ SSH ในการคอมไพล์ระบบ
* **PDF Engine:** ไลบรารี **mPDF** หรือ **Dompdf** สำหรับแปลง HTML เป็น PDF
* **UI/UX Theme:** ดัดแปลงโครงสร้างการออกแบบจากธีม **WealthWise**
    * *Web Application UI:* เน้นโทนสีครามและสเลต (Indigo & Slate) รองรับระบบ **Dark/Light Mode Theme Switching** บนหน้าเบราว์เซอร์เพื่อความสวยงาม
    * *Document Printing UI:* ใช้สไตล์ **Extreme Minimalism (Whitespace Layout)** ใช้เส้นคั่นบางๆ ไม่มีการเทสีพื้นหลังทึบ เพื่อประหยัดหมึกพิมพ์เครื่องปริ้นเตอร์สูงสุด (Ink-Saving Mode) และออกแบบด้วยโครงสร้างตารางดั้งเดิม (`<table>`) เพื่อป้องกันเลย์เอาต์เพี้ยนบนระบบแปลง PDF

---

## 3. รายละเอียดสิทธิและการเข้าถึงระบบ (Role-Based Access Control)

ระบบออกแบบโครงสร้างสิทธิ์การเข้าถึงแบบ Multi-Company Tenant Mapping:
1.  **System Admin:**
    * บริหารจัดการผู้ใช้งานทั้งหมดในระบบ (เพิ่ม แก้ไข ลบ และผูกสิทธิ์สลับไปมา)
    * เพิ่มและแก้ไขข้อมูลบริษัทได้ทุกบริษัทในระบบ (ห้ามลบตัวนิติบุคคลออกจากระบบเพื่อความปลอดภัยทางบัญชี)
    * อัปโหลดโลโก้บริษัท และเข้าถึงเมนูตั้งค่าโครงสร้างเอกสารของแต่ละบริษัท
    * เข้าดูรายงานภาพรวมรวมถึงรายงานภาษีของทุกบริษัทได้ครบถ้วน
    * มีสิทธิ์เข้าถึงเมนู **"ถังขยะ (Trash)"** ของเอกสารที่ลบเพื่อการตรวจสอบย้อนหลังแต่เพียงผู้เดียว
2.  **Company User (พนักงานบัญชีประจำบริษัท):**
    * สามารถเลือกทำงานได้เฉพาะกับบริษัทที่ตนเองได้รับสิทธิ์เท่านั้น (เช่น User 1 ดูแลบริษัท A และ C ส่วน User 2 ดูแลบริษัท A ได้แห่งเดียว)
    * สามารถเพิ่ม แก้ไข ข้อมูลรายชื่อลูกค้า และข้อมูลสินค้า/บริการได้ตามสิทธิ์ (ห้ามลบ)
    * ออกเอกสารทางบัญชีและเรียกดูรายงานสถิติต่างๆ ได้เฉพาะภายใต้ขอบเขตบริษัทที่ตนผูกสิทธิ์อยู่

---

## 4. โครงสร้างข้อมูลร่วมและข้อมูลแยกส่วน (Data Isolation & Snapshot Strategy)

เพื่อความสมดุลระหว่างความสะดวกและความเป็นส่วนตัวของข้อมูล:
* **ข้อมูลส่วนกลางที่ใช้ร่วมกัน (Shared Global Data):** ฐานข้อมูล **"รายชื่อลูกค้า (Customers)"** จะถูกแชร์เป็นคลังข้อมูลกลางรวมศูนย์สำหรับทุกบริษัท เมื่อ User พิมพ์ค้นหาด้วย "ชื่อ" หรือ "เลขประจำตัวผู้เสียภาษี (Tax ID)" ระบบจะแสดงผลค้นหาให้ดึงข้อมูลมาใช้งานได้ทันทีเพื่อลดอัตราการพิมพ์ซ้ำ
* **การทำ Data Snapshot เพื่อความถูกต้องทางกฎหมาย:** เมื่อผู้ใช้บันทึกเอกสาร ระบบจะดึงข้อมูล ชื่อ ที่อยู่ เลขผู้เสียภาษี และรหัสสาขาของลูกค้า ณ เวลานั้น มาบันทึกลงในตาราง `documents` โดยตรง (Snapshot) วิธีนี้จะช่วยล็อกข้อมูลเอกสารในอดีตให้คงที่ตลอดกาล ป้องกันไม่ให้ประวัติเปลี่ยนไปเมื่อมีการอัปเดตข้อมูลลูกค้าที่ตารางส่วนกลางในภายหลัง
* **ระบบปิดการใช้งานเพื่อซ่อนข้อมูล (Soft Inactivation):** เพื่อรักษากฎเหล็กห้ามลบข้อมูลคู่ค้าและสินค้าเด็ดขาด ระบบจึงใช้สถานะ `is_active` เป็นตัวควบคุม หากคู่ค้าใดปิดกิจการ หรือสินค้าใดเลิกจำหน่าย ผู้ใช้สามารถกดปิดการใช้งานเพื่อซ่อนรายชื่อเหล่านั้นออกจากหน้าฟอร์มกรอกเอกสารใหม่ได้ทันที โดยไม่กระทบต่อฐานข้อมูลเก่า
* **ข้อมูลแยกส่วนเฉพาะบริษัท (Isolated Tenant Data):** ฐานข้อมูล **"สินค้า/บริการ (Products & Services)"** รวมถึงรหัส SKU และเรตราคามาตรฐาน จะถูกแยกออกจากกันโดยใช้เงื่อนไข `company_id` อย่างเด็ดขาด เพื่อป้องกันความลับทางการค้าและนโยบายราคาของแต่ละนิติบุคคลรั่วไหล

---

## 5. ตรรกะการรันเลขที่เอกสารและตรรกะทางบัญชี (Core Business Logic)

* **รูปแบบเลขที่เอกสารเริ่มต้น:** รันเลขแยกอิสระตามรายเดือนและแยกเป็นรายบริษัท โดยเริ่มต้นที่โครงสร้าง ปีคริสตศักราช 4 หลักตามด้วยเดือน 2 หลัก และตัวเลขอัตโนมัติ 4 หลัก (เช่น `IV2026010001`) และระบบจะรีเซ็ตกลับไปนับ `0001` ใหม่ทุกครั้งที่ขึ้นเดือนใหม่โดยอัตโนมัติ
* **ระบบตั้งค่าเลขที่ (Prefix Setting):** ที่หน้าเมนู Setting ของผู้ดูแลระบบ Admin สามารถกำหนดคำย่อหน้าเลขเอกสาร (Prefix) ได้อย่างอิสระแยกประเภทตามข้อบันทึกของบริษัท เช่น ใบแจ้งหนี้ (IV), ใบเสร็จรับเงิน (RE), ใบกำกับภาษีเต็มรูป (TX), และใบกำกับภาษีอย่างย่อ (ABB)
* **การเชื่อมโยงระบบเอกสารอ้างอิง:** เพื่อให้สามารถติดตามกระบวนการทำงานได้ (Traceability) ระบบรองรับฟิลด์บันทึกเลขที่เอกสารเดิม เช่น การออกใบเสร็จรับเงิน สามารถเลือกระบุลิงก์จับคู่เลขที่ของใบแจ้งหนี้เดิมที่ส่งไปก่อนหน้าได้ผ่านระบบหลังบ้าน
* **ระบบสำนักงานใหญ่และสาขา:** เพื่อให้ถูกต้องตามข้อบังคับของกรมสรรพากร ฐานข้อมูลลูกค้าและเอกสารจะต้องระบุรหัสสาขา 5 หลักเสมอ โดยกำหนดให้สำนักงานใหญ่ใช้รหัส `00000` และกรณีเป็นสาขาจะใช้เลขรหัสอื่นตามที่จดทะเบียน (เช่น `00001`)
* **ระบบป้องกันเลขที่ซ้ำซ้อน (Race Condition Prevention):** เมื่อมีพนักงานกดออกเอกสารของบริษัทเดียวกันในเสี้ยววินาทีเดียวกัน ระบบจะใช้คำสั่งล็อกฐานข้อมูลชั่วคราว (**Transaction Database Lock**) เพื่อให้ระบบเรียงคิวคำนวณรหัสถัดไปได้อย่างแม่นยำ ไม่เกิดปัญหาเลขเอกสารซ้ำกันเด็ดขาด
* **ข้อจำกัดการลบเอกสาร (Soft Delete & Trash):** หากมีการออกเอกสารผิดพลาดและต้องการยกเลิก ข้อมูลจะไม่ถูกลบหายไปจาก Database จริง แต่ระบบจะเปลี่ยนสถานะเอกสารย้ายไปเข้าสู่ระบบ "ถังขยะ (Trash)" แยกรายบริษัทให้ Admin ตรวจสอบได้คนเดียว และ**ระบบจะไม่นำเลขที่เอกสารที่ถูกยกเลิกนั้นกลับมาใช้ซ้ำ** ระบบจะรันเลขที่ถัดไปทันที
* **เงื่อนไขสถานะการจดภาษีมูลค่าเพิ่ม (VAT Configuration):** ในส่วนของระบบ Setting บริษัท จะมีตัวเลือกกำหนดว่าบริษัทจดทะเบียน VAT หรือไม่ หากไม่ได้จด ระบบหลังบ้านจะทำการปิดกั้นไม่ให้เลือกเปิดฟังก์ชันออกใบกำกับภาษี (Tax Invoice) โดยอัตนุมัติ
* **ระบบภาษีหัก ณ ที่จ่าย (Withholding Tax):** โครงสร้างตารางเอกสารรองรับสัดส่วนการคำนวณหักภาษี ณ ที่จ่าย โดยมีตัวเลือก 1%, 2% หรือ 3% สรุปยอดหักลบและแสดงยอดจ่ายสุทธิ (Net Total Amount less WHT) ไว้ที่ส่วนท้ายของฟอร์มสำเร็จรูปเสมอ
* **การรองรับปริมาณเงินขนาดใหญ่ (High-Value Support):** ระบบรองรับการบันทึกตัวเลขทางการเงินและมูลค่าภาษีต่างๆ สูงสุดในระดับหลักพันล้านบาทต่อบิล เพื่อป้องกันข้อผิดพลาดในการประมวลผลโครงการขนาดใหญ่

---

## 6. การตั้งค่าสไตล์เอกสารส่วนกลาง (Global Document Customization Switches)

เพื่อลดขั้นตอนยุ่งยากหน้างาน ตัวระบบปรับปรุงให้ทำการตั้งค่าหน้าตาเอกสารเพียงครั้งเดียวที่เมนู **Company Settings** ของแต่ละบริษัท โดยจัดเก็บลงในโครงสร้าง Database เพื่อควบคุมลักษณะส่วนท้ายของเอกสารทั้งหมดภายใต้บริษัทนั้นๆ โดยอัตโนมัติ:
1.  **`show_remarks` (เปิด-ปิด ข้อความเงื่อนไข):** ติ๊กตั้งค่าเพื่อแสดงหรือซ่อนข้อความหมายเหตุทางกฎหมายเกี่ยวกับการชำระเงินและเช็คธนาคาร
2.  **`show_signatures` (เปิด-ปิด ช่องลงนาม):** ติ๊กตั้งค่าเพื่อแสดงหรือซ่อนกรอบเซ็นลายมือชื่อของผู้รับมอบอำนาจ/ผู้รับเงิน ด้านล่างสุดของกระดาษ

---

## 7. ระบบรายงานและการแจ้งเตือนอัตโนมัติ (Reporting & Automated Cron Jobs)

* **รายงานภาษีขาย (Sales Tax Report):** สรุปข้อมูลยอดรวมและยอดมูลค่าภาษีขาย (VAT) แยกรายเดือน/รายปี พร้อมฟังก์ชันการสั่งพิมพ์ใบเสร็จทุกใบรวมเป็นชุดเดียวสำหรับนำส่งฝ่ายบัญชี
* **รายงานแยกตามคู่ค้า (Customer Ledger Report):** แสดงยอดการทำธุรกรรมแยกเป็นรายบริษัทคู่ค้า พิมพ์ใบเสร็จและหนังสือรับรองการหักภาษี ณ ที่จ่ายทั้งหมดในรอบเดือนได้
* **ระบบ Audit Log (ระบบตรวจสอบประวัติการใช้งาน):** เก็บรวบรวม Log ความเคลื่อนไหวสำคัญ ได้แก่ ประวัติการล็อกอิน, การกดแก้ไขรายชื่อลูกค้า, และประวัติการกดลบเอกสารย้ายเข้าถังขยะ โดยระบุ ID ผู้กระทำ เวลา และรหัสไอเทมเพื่อความโปร่งใส
* **ระบบส่งอีเมลสรุปยอดประจำวัน (Daily Admin Notification via Cron Job):** ตั้งค่าคำสั่ง **Cron Job** บน cPanel ให้ทำงานอัตโนมัติทุกสิ้นวัน (เวลา 23:59 น.) เพื่อส่งอีเมลแจ้งเตือนไปยัง System Admin สรุปภาพรวมว่าในวันดังกล่าวมีบริษัทไหนในระบบออกเอกสารไปแล้วกี่ใบและมียอดรวมเท่าใดบ้าง

---

## 8. โครงสร้างฐานข้อมูล (Database Schema Reference)

โครงสร้างฐานข้อมูลเริ่มต้นในโปรเจกต์ `vikala-pro-app` เพื่อรองรับความต้องการทั้งหมด มีดังนี้:

```sql
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

-- 6. ตารางหลักเก็บหัวเอกสารทางบัญชี (อัปเกรดเป็น DECIMAL(12,2) รองรับยอดเงินหลักพันล้าน)
CREATE TABLE `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `document_type` enum('Invoice','Receipt','TaxInvoice','AbbrevInvoice') NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,            
  `created_date` date NOT NULL,
  -- กลุ่มฟิลด์ Customer Data Snapshot (ล็อกข้อมูลลูกค้า ณ วันที่ออกเอกสาร ป้องกันประวัติเปลี่ยนย้อนหลัง)
  `customer_name` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_tax_id` varchar(20) NOT NULL,
  `customer_branch` varchar(5) NOT NULL DEFAULT '00000',
  -- ข้อมูลคำนวณเงินทางการเงิน (ขยายขนาดรองรับ High-Value Transactions)
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

-- 7. ตารางเก็บรายการสินค้าที่ระบุภายในแต่ละเอกสาร (อัปเกรดเป็น DECIMAL(12,2))
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
```

---