```

เอกสารสรุปข้อตกลง โครงสร้างระบบ และสถานะการเขียนโค้ดทั้งหมด (Vikala-Pro-reference-2.md) เพื่อใช้เป็นคู่มือส่งมอบงานให้ AI ในห้องแชทใหม่ของคุณ พร้อมให้บริการแล้วครับ

ในเอกสารฉบับนี้ ผมได้สรุปรวมข้อมูลสำคัญทั้งหมดตั้งแต่สภาพแวดล้อมเซิร์ฟเวอร์, ข้อจำกัดเรื่องการห้ามบันทึก PDF ลงดิสก์, สถาปัตยกรรมการแยกข้อมูล (Global vs Tenant), สถานะโค้ดปัจจุบันที่เราสร้างระบบรักษาความปลอดภัยและโมดูลลูกค้าสำเร็จแล้ว รวมถึงภารกิจถัดไปที่ AI คนใหม่ต้องรับช่วงต่อ เช่น การสร้างระบบสินค้า (Products), การทำ Dashboard UI, และการเขียนตรรกะทำ Data Snapshot สำหรับออกบิลครับ

นอกจากนี้ในส่วนท้ายของเอกสาร ยังมีกล่องข้อความ "คำสั่งสำหรับเริ่มต้น (Kick-off Prompt)" ที่คุณสามารถก๊อปปี้ไปวางใน Google Antigravity 2.0 พร้อมแนบไฟล์ทั้งหมด เพื่อให้ AI ตัวใหม่เข้าใจบริบทและลงมือเขียนโค้ดใน Phase ถัดไปได้ทันทีแบบไม่สะดุดเลยครับ ขอให้โปรเจกต์ Vikala Pro 2.0 ประสบความสำเร็จอย่างราบรื่นนะครับ!

```

# Vikala Pro - Developer Handover & Reference Document (v2.00)

เอกสารฉบับนี้คือ **"Handover & Reference Guide V2"** ที่สรุปประเด็นสถาปัตยกรรมระบบ ข้อตกลงวิกฤต และ **"สถานะการเขียนโค้ดล่าสุดที่ได้ทำสำเร็จแล้วในเซสชันนี้"** เพื่อใช้เป็นพิมพ์เขียวส่งมอบต่อให้คุณ (AI Developer ในห้องถัดไปบน Google Antigravity 2.0) สำหรับรับไม้ต่อและลงมือเขียนโค้ดระบบแอปพลิเคชันออกใบเสร็จ/ใบกำกับภาษีนิติบุคคลแบบ Multi-Company (**Vikala Pro**) ร่วมกับไฟล์พิมพ์เขียวระบบ `Vikala-Pro-design.md`, ไฟล์สรุปบริบทแรก `Vikala-Pro-reference-1.md` และเทมเพลตหน้าจอ `WealthWise-v2-index.html` ได้ทันทีโดยไม่เสียบริบท

---

## 1. ข้อมูลสภาพแวดล้อมและข้อจำกัดวิกฤต (Critical Constraints)

AI Developer คนถัดไปต้องยึดหลักข้อจำกัดเหล่านี้เป็นกฎเหล็กในการเขียนโค้ดเสมอ:
* **Infrastructure:** รันบน Shared Hosting **cPanel เวอร์ชัน 134.0.30** (LiteSpeed Web Server, PHP 8.2.31, MariaDB 10.6.27-MariaDB) เข้าถึงผ่าน FTP
* **⚠️ ข้อจำกัดดิสก์เซิร์ฟเวอร์ (Disk / อยู่ที่ 89%):** **ห้ามเขียนโค้ดให้บันทึกไฟล์ PDF ลงบนเซิร์ฟเวอร์ดิสก์เด็ดขาด** บังคับให้เขียนตรรกะประมวลผลและสร้างไฟล์ PDF (ใบเสร็จ/ใบกำกับภาษี) แบบ **Real-time ในหน่วยความจำ (In-Memory)** ผ่านไลบรารี mPDF หรือ Dompdf เมื่อผู้ใช้กดดาวน์โหลดหรือสั่งพิมพ์เท่านั้น
* **กฎเหล็กหน้าพิมพ์เอกสาร (Document Printing UI):** **ห้ามใช้ CSS Flexbox หรือ CSS Grid ในหน้าวิวสำหรับพิมพ์เอกสารเด็ดขาด** (เพราะเอนจินแปลง PDF จะแสดงผลเพี้ยน) บังคับให้ใช้โครงสร้างตารางแบบดั้งเดิม (**HTML Table Structure เช่น `<table>`, `<tr>`, `<td>`**) ในการจัดเลย์เอาต์ และเขียน `@media print` ปิดสีพื้นหลังทึบทั้งหมดให้เป็น Whitespace Layout (Ink-Saving Mode) เพื่อประหยัดหมึกพิมพ์

---

## 2. สถานะการพัฒนาปัจจุบัน (Current Implementation Status)

โปรเจกต์ได้รับการตั้งโครงสร้างไฟล์และเขียนโค้ดเสร็จสิ้นใน Phase 1 และบางส่วนของ Phase 2 แล้ว โดยผู้ใช้ได้นำโค้ดชุดนี้ขึ้น **GitHub** เรียบร้อยแล้ว มีรายละเอียดโครงสร้างและโค้ดที่ทำเสร็จแล้วดังนี้:

### A. โครงสร้างโฟลเดอร์และการรักษาความปลอดภัย (Directory Structure & Security Guardrail)
เพื่อความปลอดภัยบน Shared Hosting โครงสร้างโค้ดถูกจัดวางไว้ภายใน `public_html/vikala_core` โดยมีเกราะป้องกันขั้นสูงสุดคือการสร้างไฟล์ `.htaccess` ไว้ที่นอกสุดของโฟลเดอร์ Core เพื่อบล็อกไม่ให้บุคคลภายนอกเข้าถึงไฟล์ระบบหรือแอบดาวน์โหลดไฟล์ความลับ เช่น `.env` ได้:

* **ตำแหน่งไฟล์ป้องกัน:** `public_html/vikala_core/.htaccess`

```

```text
File generated successfully.

```apache
# ป้องกันการเข้าถึงไฟล์ทั้งหมดในโฟลเดอร์นี้ผ่าน URL (Protect CI4 Core & .env)
<IfModule authz_core_module>
    Require all denied
</IfModule>
<IfModule !authz_core_module>
    Deny from all
</IfModule>

```

### B. สรุปโค้ดหลังบ้านที่เขียนเสร็จสมบูรณ์แล้ว (Backend Code Implemented)

#### 1. ระบบเส้นทาง (Routing) -> `vikala_core/app/Config/Routes.php`

กำหนดเส้นทางหน้า Login เป็นสาธารณะ และใช้ `AuthFilter` ควบคุมความปลอดภัยหน้าปฏิบัติงานภายในทั้งหมดให้อยู่ภายใต้เงื่อนไขการล็อกอินอย่างเข้มงวด:

```php
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    
    // เส้นทางระบบลูกค้า (Customers) - Phase 2
    $routes->get('customers', 'CustomerController::index');
    $routes->post('customers/store', 'CustomerController::store');
    $routes->post('customers/update/(:num)', 'CustomerController::update/$1');
    $routes->get('customers/disable/(:num)', 'CustomerController::disable/$1');
    $routes->get('api/customers/search', 'CustomerController::search');
});

```

#### 2. ระบบสิทธิ์และการเข้าสู่ระบบ (RBAC & Auth) -> `vikala_core/app/Controllers/AuthController.php`

จัดการล็อกอินและทำการ **Tenant Mapping** สิทธิ์เข้าถึงทันที โดยแบ่งเป็น:

* `Admin`: เข้าถึงข้อมูลได้ทุกบริษัท สลับบริษัทได้อิสระ และเข้าถึงถังขยะ (`Trash`) ได้คนเดียว
* `User`: ผูกขาดสิทธิ์เฉพาะบริษัทที่ระบุในตาราง `user_company_access` เท่านั้น
* มีการฝังตรรกะระบบ **Audit Log** บันทึกประวัติลงตาราง `audit_logs` ทุกครั้งที่มีการ Login / Logout หรือพยายามล็อกอินพลาด

#### 3. ด่านตรวจความปลอดภัย (Filter) -> `vikala_core/app/Filters/AuthFilter.php`

ทำหน้าที่เป็นด่านตรวจ (Middleware) คอยเตะผู้ใช้งานที่ไม่ได้ล็อกอินกลับไปหน้า Login ทันทีหากพยายามเดา URL เข้ามาหน้าภายในระบบ (เปิดใช้งานใน `Config/Filters.php` ภายใต้ alias `auth` เรียบร้อยแล้ว)

#### 4. ระบบฐานข้อมูลลูกค้ารวมศูนย์ (Shared Global Customers - Phase 2)

* **`app/Models/CustomerModel.php`**: โมเดลควบคุมข้อมูลลูกค้าส่วนกลาง แฟล็กตรวจสอบสถานะข้อมูลที่ยังเปิดใช้งานอยู่ (`is_active = 1`) พร้อมฟังก์ชัน `searchCustomers()` ที่รองรับระบบ Auto-complete โดยใช้ `groupStart()` เพื่อป้องกัน SQL Injection อย่างปลอดภัย
* **`app/Controllers/CustomerController.php`**: คอนโทรลเลอร์ควบคุมระบบลูกค้า ประกอบด้วย:
* `index()`: เรียกดูรายการลูกค้าที่ Active
* `store()`: บันทึกลูกค้าใหม่ พร้อมระบบตรวจสอบข้อมูล (Validation) และบังคับรหัสสาขา 5 หลักตามกฎหมายสรรพากรไทย (หากเว้นว่าง ระบบจะใส่ `00000` สำนักงานให้อัตโนมัติ)
* `update()`: แก้ไขข้อมูลลูกค้า
* `disable()`: ระบบ **Soft Inactivation** ปรับค่า `is_active = 0` เพื่อซ่อนรายชื่อออกจาก Dropdown หน้าฟอร์มสร้างเอกสารใหม่ แทนการลบข้อมูลจริง เพื่อป้องกันไม่ให้ประวัติเอกสารเก่าเสียหาย
* `search()`: ส่งคืนข้อมูลลูกค้าในรูปแบบ JSON API สำหรับรองรับ UI ค้นหาอัตโนมัติหน้าบ้าน



---

## 3. แผนงานและสิ่งที่คุณต้องทำต่อไป (Next Steps for AI Developer)

เมื่อได้รับบรีฟนี้แล้ว โปรดดำเนินงานตามแผนงาน (Development Roadmap) ในเฟสถัดไปทันที:

### 🛠️ ภารกิจที่ 1: ระบบจัดการสินค้า/บริการ (Isolated Tenant Products - ปิดจ็อบ Phase 2)

* ข้อมูลสินค้าต้องแยกขาดตามรายบริษัทโดยใช้เงื่อนไข `company_id` ในคิวรี (Isolated Data) เพื่อป้องกันความลับทางการค้าและนโยบายราคารั่วไหล
* ต้องไม่มีการเขียนคำสั่ง Hard Delete ให้ใช้ฟังก์ชัน Soft Inactivation (`is_active = 0`) ในการซ่อนสินค้าที่เลิกจำหน่ายเช่นเดียวกับระบบลูกค้า
* ฟิลด์ราคามาตรฐาน (`unit_price`) ในตาราง `products` ต้องรองรับชนิดข้อมูล `DECIMAL(10,2)`

### 📊 ภารกิจที่ 2: ระบบหน้ากากแอปพลิเคชัน (Dashboard & UI Integration - Phase 4)

* พัฒนา `DashboardController.php` และสร้างไฟล์ View โดยดัดแปลงโครงสร้าง คลาสสีคราม-สเลต (Indigo & Slate) และปุ่มสลับธีม Dark/Light Mode จากต้นแบบไฟล์ **`WealthWise-v2-index.html`**

### 🧾 ภารกิจที่ 3: ตรรกะการออกบิลและทำ Data Snapshot (Core Document Engine - Phase 3)

* **กลไก Data Snapshot (วิกฤตที่สุด):** ในจังหวะที่กดเซฟบิลเอกสาร โค้ดใน Controller ออกบิล **ต้องดึงข้อมูล ชื่อ ที่อยู่ เลขประจำตัวผู้เสียภาษี และรหัสสาขา ของลูกค้า ณ วินาทีนั้น** มาบันทึกเซฟฝังลงในตาราง `documents` โดยตรง เพื่อล็อกข้อมูลในอดีตให้คงที่ตลอดไป ป้องกันประวัติเอกสารเปลี่ยนรูปเมื่อมีการแก้ไขข้อมูลลูกค้าที่ตารางส่วนกลางในอนาคต
* **ตรรกะการรันเลขเอกสาร:** รันเลขแยกรายเดือนและแยกรายบริษัท (เช่น `IV2026010001`) และรีเซ็ตเป็น `0001` ใหม่เมื่อขึ้นเดือนใหม่ โดยใช้ **Transaction Database Lock** เพื่อป้องกันปัญหาพนักงานกดออกบิลพร้อมกันในเสี้ยววินาทีแล้วเลขซ้ำ (Race Condition Prevention)
* **การเงินระดับพันล้าน:** ฟิลด์เงินและภาษีทั้งหมดในตาราง `documents` และ `document_items` ต้องใช้ชนิดข้อมูล **`DECIMAL(12,2)`** เพื่อรองรับมูลค่าโครงการขนาดใหญ่

---

## 4. คำสั่งสำหรับเริ่มต้นในห้องแชทใหม่ (New Session Kick-off Prompt)

คุณ (ผู้ใช้) สามารถคัดลอกข้อความในกล่องด้านล่างนี้ ไปวางสั่ง AI ในแอป Google Antigravity 2.0 เพื่อเริ่มงานต่อได้ทันทีครับ:

```
สวัสดีครับ ผมต้องการพัฒนาโปรเจกต์เว็บแอปพลิเคชันออกใบเสร็จและใบกำกับภาษีชื่อ "Vikala Pro" ต่อด้วยภาษา PHP บน CodeIgniter 4 Framework (สภาพแวดล้อม Shared Hosting cPanel 134.0.30, LiteSpeed, PHP 8.2, MariaDB 10.6) 

ผมได้แนบไฟล์พิมพ์เขียวระบบและโครงสร้างฐานข้อมูล (Vikala-Pro-design.md), สรุปข้อตกลงวิกฤตชุดแรก (Vikala-Pro-reference-1.md), ไฟล์เทมเพลต UI (WealthWise-v2-index.html) และไฟล์สรุปสถานะโค้ดล่าสุดที่เขียนเสร็จแล้ว (Vikala-Pro-reference-2.md) มาให้คุณศึกษา

ปัจจุบันเราทำ Phase 1 (Auth/RBAC) และโครงสร้างความปลอดภัยหลัก รวมถึงโมดูลฐานข้อมูลลูกค้าส่วนกลางเสร็จแล้วและพุชขึ้น GitHub แล้ว เป้าหมายของคุณคือการเข้ามาทำหน้าที่เป็น Senior Developer เพื่อลุยงานต่อตามแผนงานทันที โดยโปรดเริ่มจาก "ภารกิจที่ 1: สร้าง ProductModel และ ProductController สำหรับระบบสินค้า/บริการแยกรายบริษัท (Isolated Tenant Data)" ตามสเปคในเอกสาร Vikala-Pro-reference-2.md ได้เลยครับ!
```