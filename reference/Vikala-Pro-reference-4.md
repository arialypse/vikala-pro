# Vikala Pro - Developer Handover, Action Plan & Priority Guide (v4.1)

เอกสารฉบับนี้คือ **"Action Plan & Priority Guide V4.1"** สำหรับประเมินสถานะล่าสุด รวบรวมแผนงานที่ต้องทำต่อไป (Next Action Plan) และจัดลำดับความสำคัญของงาน (Prioritization) เพื่อให้โปรเจกต์เว็บแอปพลิเคชันออกใบเสร็จและใบกำกับภาษี **"Vikala Pro"** สำเร็จลุล่วงและพร้อมใช้งานจริงบน Shared Hosting (cPanel)

---

## 📊 1. การตรวจสอบสถานะปัจจุบันบน GitHub (Current Audit Status)
จากการสแกน Repository ล่าสุด โครงสร้างฝั่งโมเดล (Models) และคอนโทรลเลอร์ (Controllers) ได้รับการวางฐานข้อมูลและตรรกะทางธุรกิจที่สำคัญไว้ครบถ้วนแล้ว:
* **ระบบความปลอดภัยหลัก:** วางเกราะป้องกันไฟล์ระบบและไฟล์ความลับด้วย `.htaccess` ที่นอกสุดของโฟลเดอร์ Core
* **ระบบข้อมูลแยกส่วน (Tenant Data) & Snapshot:** มีการทำ Multi-Company Mapping ในเซสชัน ระบบดักล็อกข้อมูลลูกค้า (Data Snapshot) ลงตารางบิลโดยตรง และใช้คำสั่ง `FOR UPDATE` เพื่อป้องกันเลขบิลซ้ำซ้อน
* **ระบบ In-Memory PDF:** คอนโทรลเลอร์สั่งเรนเดอร์ตาราง HTML ร่วมกับไลบรารี mPDF พ่นออกเบราว์เซอร์โดยตรง ไม่เก็บไฟล์ลงดิสก์

---

## 🎯 2. แผนงานที่ต้องทำต่อไป (Next Action Plan)
เพื่อให้ระบบทำงานได้จริง 100% (End-to-End) นี่คือ "จิ๊กซอว์" ที่ยังขาดหายไปและต้องพัฒนาต่อ:

**2.1 เติมเต็มหน้าจอ UI ที่ยังขาดหาย (Missing Views Implementation)**
* สร้างหน้าจอเพื่อให้ Controller ที่เขียนไว้สามารถแสดงผลได้จริง ได้แก่:
  * หน้าล็อกอิน (`auth/login.php`)
  * หน้าจัดการลูกค้า (`customers/index.php`)
  * หน้าจัดการสินค้า (`products/index.php`)

**2.2 พัฒนาระบบตั้งค่าบริษัท (Company Settings Module)**
* สร้างหน้าจอให้ Admin ตั้งค่า Prefix เอกสาร (IV, RE, TX, ABB) และเปิด-ปิดตัวเลือก `show_remarks`, `show_signatures`
* สร้าง `CompanyController`, `CompanyModel` และหน้า View สำหรับตั้งค่า

**2.3 จัดการโครงสร้างพื้นฐานและ Dependencies (Infrastructure & Packages)**
* สร้างไฟล์ `composer.json` เพื่อติดตั้งไลบรารี `mpdf/mpdf` สำหรับออกเอกสาร PDF
* เตรียมสคริปต์ SQL Database ทั้งหมดเพื่อให้พร้อมนำไปรันบน phpMyAdmin ของ cPanel

**2.4 ระบบรายงานและ Cron Job (Reporting & Automation)**
* สร้างหน้าจอรายงานสรุป (Sales Tax Report)
* เขียนสคริปต์ Command สำหรับส่งอีเมลสรุปยอดประจำวัน เพื่อนำไปตั้งค่า Cron Job บน cPanel

---

## 🚀 3. การจัดลำดับความสำคัญของการพัฒนา (Prioritization Roadmap)
ขอเรียงลำดับความสำคัญของภารกิจที่ AI Developer ต้องลงมือทำทันทีจาก วิกฤตที่สุด (Priority 1) ไปจนถึงขั้นสูง (Priority 4):

* **[Priority 1] การสร้างไฟล์ View (UI) ที่ยังขาดหาย:** เริ่มจากการสร้างหน้า `auth/login.php`, `customers/index.php` และ `products/index.php` ด้วย Tailwind CSS โทนสีคราม-สเลต (Indigo & Slate) และรองรับ Dark/Light Mode เพื่อให้ระบบที่มีอยู่แสดงผลได้ก่อน
* **[Priority 2] การจัดเตรียมโครงสร้างพื้นฐานและ Dependencies:** สร้าง `composer.json` และไฟล์ `database.sql` สำหรับนำไปรันบนเซิร์ฟเวอร์
* **[Priority 3] ระบบตั้งค่านิติบุคคล (Company Settings Module):** สร้าง Model, Controller และ View สำหรับจัดการตาราง `companies` เพื่อตั้งค่าหน้าตากระดาษและ Prefix
* **[Priority 4] ระบบรายงานและการส่งอีเมลอัตโนมัติ:** พัฒนาระบบรายงานภาษีขาย, รายงานคู่ค้า และสคริปต์ Cron Job แจ้งเตือนยอดรายวัน

---