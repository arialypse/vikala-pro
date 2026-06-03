# 📊 Vikala Pro - Web Application for Accounting & Invoice Management

ระบบจัดการใบเสร็จ ใบกำกับภาษี และรายงานบัญชี สำหรับสำนักงานบัญชีและผู้ประกอบการหลายสาขา

## 🎯 บทบาท (Overview)

**Vikala Pro** เป็นเว็บแอปพลิเคชันสำหรับจัดการเอกสารทางบัญชี ที่ออกแบบมาเพื่อ:
- ✅ ออกใบเสร็จและใบกำกับภาษีได้หลายรูปแบบ (Invoice, Receipt, Tax Invoice, Abbreviated Invoice)
- ✅ จัดการข้อมูลลูกค้าและสินค้า/บริการหลากหลาย
- ✅ รองรับการจัดการหลายสาขา (Multi-Company/Multi-Tenant)
- ✅ ระบบสิทธิ์การเข้าถึง (Role-Based Access Control)
- ✅ รายงานภาษีขายและการตรวจสอบประวัติ (Audit Log)
- ✅ ส่งออกเอกสารเป็น PDF พร้อมตั้งค่าระดับสัทธานา

---

## 🏗️ สถาปัตยกรรมระบบ (System Architecture)

### เทคโนโลยีที่ใช้
| ส่วนประกอบ | เทคโนโลยี |
|-----------|----------|
| **Backend Framework** | PHP 8.2.31 + CodeIgniter 4 (CI4) |
| **Database** | MariaDB 10.6.27 |
| **Web Server** | LiteSpeed Web Server (cPanel 134.0.30) |
| **PDF Engine** | mPDF หรือ Dompdf |
| **Front-end Theme** | ดัดแปลงจาก WealthWise Theme (Indigo & Slate) |
| **Hosting** | Shared Hosting (cPanel) |

### ข้อจำกัดสภาพแวดล้อมที่สำคัญ (Critical Constraints)
- 🛑 **ดิสก์เซิร์ฟเวอร์**: ใกล้เต็ม (89%) - ห้ามเก็บไฟล์ขนาดใหญ่
- ⚙️ **PHP Memory**: 2GB, Upload Max: 512MB, Execution Time: 60 วินาที
- 🔒 **Hosting**: Shared Hosting ผ่าน FTP Access เท่านั้น

---

## 📁 โครงสร้างโปรเจกต์ (Project Structure)

```
vikala-pro/
├── vikala_core/                    # โฟลเดอร์หลักของแอปพลิเคชัน (CI4 App)
│   ├── app/
│   │   ├── Controllers/            # ลอจิก Controller
│   │   │   ├── AuthController.php          # ระบบล็อกอินและ RBAC
│   │   │   ├── DashboardController.php     # แดชบอร์ด
│   │   │   ├── CustomerController.php      # จัดการลูกค้า
│   │   │   ├── ProductController.php       # จัดการสินค้า
│   │   │   └── DocumentController.php      # ระบบออกเอกสาร
│   │   ├── Models/                 # โมเดลฐานข้อมูล
│   │   │   ├── UserModel.php
│   │   │   ├── CustomerModel.php
│   │   │   ├── ProductModel.php
│   │   │   └── DocumentModel.php
│   │   ├── Views/                  # ไฟล์ View (Blade/PHP Template)
│   │   │   ├── auth/
│   │   │   ├── dashboard/
│   │   │   ├── customers/
│   │   │   ├── products/
│   │   │   └── documents/
│   │   ├── Filters/                # Middleware
│   │   │   └── AuthFilter.php      # ตรวจสอบสิทธิ์เข้าถึง
│   │   └── Config/
│   │       └── Routes.php          # เส้นทาง (Routes)
│   ├── database/
│   │   └── migrations/             # SQL Schema
│   ├── public/
│   │   ├── css/
│   │   ├── js/
│   │   └── assets/
│   ├── .env                        # ตัวแปรสภาพแวดล้อม
│   ├── .htaccess                   # ป้องกันการเข้าถึง
│   └── composer.json               # Dependencies (mPDF, etc.)
├── reference/
│   ├── Vikala-Pro-design.md        # เอกสารออกแบบระบบ (v4.00)
│   ├── Vikala-Pro-reference-1.md   # Developer Handover (v1.00)
│   ├── Vikala-Pro-reference-2.md   # Handover & Reference (v2.00)
│   ├── Vikala-Pro-reference-3.md   # Git Workflow & Guardrails
│   ├── Vikala-Pro-reference-4.md   # Action Plan & Priority (v4.1)
│   ├── Skill/                      # ไฟล์อ้างอิงเพิ่มเติม
│   └── Theme/                      # ไฟล์เทมเพลท UI
├── .gitignore
├── .htaccess
├── LICENSE                         # Apache License 2.0
└── README.md                       # ไฟล์นี้

```

---

## 🔐 ระบบสิทธิ์การเข้าถึง (Role-Based Access Control)

### บทบาทผู้ใช้ (User Roles)

#### 1️⃣ System Admin
- บริหารจัดการผู้ใช้ทั้งระบบ (เพิ่ม/แก้ไข/ลบ/ผูกสิทธิ์)
- เข้าถึงข้อมูลทุกบริษัท
- ตั้งค่า Prefix เอกสาร และสวิตช์เปิด-ปิดฟีเจอร์
- เข้าดูรายงานภาษีขายรวมทั้งระบบ
- เข้าถึง Trash ของเอกสารที่ลบ

#### 2️⃣ Company User (พนักงานบัญชี)
- ทำงานได้เฉพาะกับบริษัทที่ได้สิทธิ์
- เพิ่ม/แก้ไข ข้อมูลลูกค้าและสินค้า
- ออกเอกสารทางบัญชี
- ดูรายงานเฉพาะบริษัทของตนเอง

---

## 💾 โครงสร้างฐานข้อมูล (Database Schema)

### ตารางหลัก (Core Tables)

| ตาราง | ลักษณะ | จุดประสงค์ |
|------|--------|----------|
| **companies** | เก็บข้อมูลบริษัท | นิติบุคคล, ตั้งค่าระบบ, Prefix |
| **users** | เก็บบัญชีผู้ใช้ | ชื่อ, รหัสผ่าน, สิทธิ์ |
| **user_company_access** | ผูกสิทธิ์ | ระบุว่า User สามารถเข้าถึงบริษัทใดได้บ้าง |
| **customers** | ลูกค้า (Shared Global) | ชื่อ, ที่อยู่, Tax ID (ใช้ร่วมทั้งระบบ) |
| **products** | สินค้า/บริการ (Tenant-Isolated) | SKU, ราคา, สิ่งที่ขาย (แยกเป็นรายบริษัท) |
| **documents** | เอกสารหลัก | ใบเสร็จ, ใบกำกับภาษี, ข้อมูลทางการเงิน |
| **document_items** | รายการสินค้าในเอกสาร | สินค้า/บริการแต่ละแถว |
| **audit_logs** | บันทึกประวัติ | การเข้าถึง, การแก้ไข, IP Address |

---

## 🎯 ลอจิกทางธุรกิจหลัก (Core Business Logic)

### 1. Data Snapshot (ป้องกันข้อมูลเปลี่ยนรูป)
เมื่อบันทึกเอกสาร ระบบ**บันทึกข้อมูลลูกค้าสแนปชอตไว้**ในเอกสารด้วย:
```sql
-- ตาราง documents มีฟิลด์:
customer_name, customer_address, customer_tax_id, customer_branch
-- ป้องกันสถานการณ์: ลูกค้าเปลี่ยนที่อยู่ แต่เอกสารเก่ายังเก็บข้อมูลเดิม
```

### 2. ระบบรันเลขเอกสาร (Document Numbering)
- รันเลขแยกตามรายเดือนและรายบริษัท
- รูปแบบ: `IV202601001` (Prefix + YYYYMM + Sequential)
- **ป้องกัน Race Condition**: ใช้ `unique constraint` และ `transaction`
- การลบอ้างอิง (Soft Delete): เลขที่เอกสารย้ายไปถังขยะ (Trash)

### 3. ระบบภาษี (Thai Tax Compliance)
- **VAT (Value Added Tax)**: สนับสนุนการคำนวณภาษีมูลค่าเพิ่ม 7%
- **WHT (Withholding Tax)**: ห้ามแขวนครอบการหักภาษี ณ ที่จ่าย
- **รหัสสาขา 5 หลัก**: บังคับให้มีรหัสสาขา (branch_code)
- **High-Value Support**: เสมือนรองรับใบเสร็จสูงถึงระดับพันล้าน (DECIMAL 12,2)

### 4. Soft Inactivation (ซ่อนข้อมูลแทนลบ)
- ไม่ลบข้อมูลลูกค้าหรือสินค้า เพื่อรักษาสมบูรณ์ประวัติ
- ใช้ฟิลด์ `is_active = 0` เพื่อซ่อนรายชื่อออกจาก Dropdown

---

## 🎨 ออกแบบ UI/UX (Design & Print Mechanics)

### หน้าจอแอปพลิเคชันเว็บ (Web Dashboard)
- ธีม: ดัดแปลงจาก **WealthWise Theme**
- สีหลัก: Indigo & Slate
- ฟีเจอร์: **Dark/Light Mode Theme Switching**
- ส่วนประกอบ: Navigation, Sidebar, Content Area, Footer

### ระบบพิมพ์เอกสาร (Document Printing)
- ⚠️ **กฎเหล็ก**: ห้ามใช้ CSS Flexbox/Grid ในหน้าพิมพ์
- ใช้: **HTML Table Structure** แบบดั้งเดิม
- สไตล์: **Extreme Minimalism** (Whitespace Layout, เส้นคั่นบาง ไม่มีสีพื้นหลัง)
- **Ink-Saving Mode**: ล้างค่าสีพื้นหลังใน `@media print`

---

## 📊 ระบบรายงาน (Reporting System)

1. **Sales Tax Report** - สรุปยอดรวมและมูลค่า VAT แยกรายเดือน
2. **Customer Ledger Report** - ยอดการทำธุรกรรมแยกรายลูกค้า
3. **Audit Log** - บันทึกประวัติการใช้งาน (User, Action, Timestamp, IP)
4. **Daily Admin Notification** - ส่งอีเมลสรุปยอดประจำวัน (Cron Job)

---

## 🚀 การเริ่มต้น (Quick Start)

### ข้อกำหนดเบื้องต้น
- PHP 8.2.31+
- MariaDB 10.6.27+
- Composer (สำหรับจัดการ Dependencies)
- FTP Client (สำหรับ cPanel Upload)

### ขั้นตอนการติดตั้ง

1. **Clone Repository**
   ```bash
   git clone https://github.com/arialypse/vikala-pro.git
   cd vikala-pro
   ```

2. **ตั้งค่าไฟล์ Environment**
   ```bash
   cp vikala_core/.env.example vikala_core/.env
   # แก้ไขค่าต่อไปนี้:
   # CI_ENVIRONMENT = production
   # database.default.hostname = localhost
   # database.default.database = vikala_pro
   # database.default.username = root
   # database.default.password = *****
   ```

3. **ติดตั้ง Dependencies**
   ```bash
   cd vikala_core
   composer install
   ```

4. **นำเข้าฐานข้อมูล**
   - ใช้ phpMyAdmin ของ cPanel
   - นำเข้าไฟล์ `database.sql` (อยู่ใน `vikala_core/database`)

5. **ตั้งค่าเวบเซิร์ฟเวอร์**
   - `public_html/vikala_core` ต้องชี้ไปยังโฟลเดอร์ `vikala_core/public`
   - ตรวจสอบไฟล์ `.htaccess` เพื่อป้องกันการเข้าถึง

6. **เข้าสู่ระบบ**
   - เปิด `https://yourdomain.com/`
   - Username: admin / Password: [ตามการตั้งค่า]

---

## 🔄 Git Workflow (สำหรับ AI Developer)

### Strict Workflow
1. **เขียนโค้ดทีละขั้น** (ไม่รวดเดียว)
2. **ตรวจสอบการเปลี่ยนแปลง** → `git status`
3. **Commit เป็นจุดปลอดภัย** → `git commit -m "สั่นเงิน Description"`
4. **สรุปและรอคำสั่ง** → รายงานผลให้ผู้ใช้ก่อน
5. **Push เมื่อจบเสร็จ** → `git push origin main`

---

## 📋 แผนงานการพัฒนา (Development Roadmap)

### Phase 1 ✅ (COMPLETED)
- ระบบความปลอดภัยและ RBAC
- ระบบ Audit Log

### Phase 2 (Current)
- ✅ ระบบจัดการลูกค้า (Customer Management)
- ⏳ ระบบจัดการสินค้า/บริการ (Product Management)

### Phase 3 (Next)
- ตรรกะการออกบิลและ Data Snapshot
- ระบบรันเลขเอกสาร
- PDF Generation

### Phase 4 (Future)
- แดชบอร์ด UI/UX Integration
- ระบบรายงาน

### Phase 5 (Later)
- ระบบตั้งค่าบริษัท
- Cron Job & Email Notification

---

## 🔒 ความปลอดภัยและการวิจารณ์ (Security & Compliance)

- ✅ **Authentication**: Secure Password Hashing (bcrypt)
- ✅ **Authorization**: Role-Based Access Control (RBAC)
- ✅ **Data Isolation**: Multi-Company Tenant Mapping
- ✅ **Audit Trail**: บันทึกการกระทำทั้งหมด
- ✅ **Soft Delete**: ห้ามลบข้อมูลจริง เพื่อรักษาความสมบูรณ์
- ⚠️ **Thai Tax Compliance**: รองรับกฎหมายภาษีไทย

---

## 📞 ติดต่อและสนับสนุน (Support)

- 👨‍💼 **Developer**: arialypse
- 📧 **Email**: [contact info]
- 🐙 **GitHub**: https://github.com/arialypse/vikala-pro
- 📄 **License**: Apache License 2.0

---

## 📚 เอกสารอ้างอิง (Documentation)

- `reference/Vikala-Pro-design.md` - เอกสารออกแบบระบบสมบูรณ์
- `reference/Vikala-Pro-reference-1.md` - Developer Handover
- `reference/Vikala-Pro-reference-2.md` - Implementation Status & Roadmap
- `reference/Vikala-Pro-reference-3.md` - Git Workflow Guardrails
- `reference/Vikala-Pro-reference-4.md` - Action Plan & Priorities

---

**Last Updated**: June 3, 2026  
**Version**: 1.0  
**Status**: 🔄 In Development (Phase 2)
