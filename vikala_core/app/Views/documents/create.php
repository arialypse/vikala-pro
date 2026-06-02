<!DOCTYPE html>
<html lang="th" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vikala Pro - ออกเอกสารใหม่</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F3F4F9; transition: background-color 0.3s ease, color 0.3s ease; }
        .dark body { background-color: #0f172a; }
        .sidebar-active { background-color: #EEF2FF; color: #4F46E5; border-right: 4px solid #4F46E5; }
        .dark .sidebar-active { background-color: #1e1b4b; color: #818cf8; border-right: 4px solid #818cf8; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        .autocomplete-list { position: absolute; z-index: 50; top: 100%; left: 0; right: 0; max-height: 200px; overflow-y: auto; }
        .autocomplete-list li:hover { background-color: #EEF2FF; }
        .dark .autocomplete-list li:hover { background-color: #1e1b4b; }
    </style>
</head>
<body class="flex min-h-screen text-slate-700 dark:text-slate-300 antialiased">

    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-slate-800 border-r border-slate-100 dark:border-slate-700 flex flex-col justify-between p-6 shrink-0 z-20 transition-colors duration-300">
        <div>
            <div class="flex items-center gap-3 mb-10 pl-2">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">V</div>
                <span class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Vikala Pro</span>
            </div>
            <nav class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block pl-2 mb-2">Menu</span>
                <a href="<?= base_url('dashboard') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-left">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard
                </a>
                <a href="<?= base_url('customers') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-left">
                    <i class="fa-solid fa-users w-5 text-center"></i> Customers
                </a>
                <a href="<?= base_url('products') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-left">
                    <i class="fa-solid fa-box w-5 text-center"></i> Products
                </a>
                <a href="<?= base_url('documents') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all sidebar-active text-left">
                    <i class="fa-solid fa-file-invoice w-5 text-center"></i> Documents
                </a>
            </nav>
        </div>
        <div>
            <div class="bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-lg shrink-0"><i class="fa-solid fa-user-tie"></i></div>
                    <div>
                        <h4 class="font-semibold text-slate-900 dark:text-white text-sm mb-1"><?= esc(session()->get('fullname')) ?></h4>
                        <p class="text-xs text-indigo-900/80 dark:text-indigo-200/80 leading-relaxed mb-2">Role: <?= esc(session()->get('role')) ?></p>
                    </div>
                </div>
            </div>
            <div class="space-y-1 text-sm">
                <a href="<?= base_url('logout') ?>" class="flex items-center gap-4 px-4 py-2.5 text-rose-500 font-medium rounded-xl hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Logout
                </a>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 h-screen">
        
        <!-- Header -->
        <header class="h-20 bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between px-10 shrink-0 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <a href="<?= base_url('documents') ?>" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight uppercase">ออกเอกสารใหม่</h1>
            </div>
            <button id="theme-toggle" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-all relative">
                <i id="theme-icon" class="fa-solid fa-moon"></i>
            </button>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-10 space-y-8 dark:bg-slate-900 transition-colors duration-300">

            <!-- Flash Messages -->
            <?php if(session()->getFlashdata('error')): ?>
                <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-6 py-4 rounded-2xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span class="font-medium"><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-6 py-4 rounded-2xl">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        <?php foreach(session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('documents/store') ?>" method="post" id="documentForm">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Left Column: Document Info + Customer -->
                    <div class="lg:col-span-2 space-y-8">

                        <!-- Document Type & Date -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm p-6 transition-colors duration-300">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6"><i class="fa-solid fa-file-lines mr-2 text-indigo-500"></i>ข้อมูลเอกสาร</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2">ประเภทเอกสาร <span class="text-rose-500">*</span></label>
                                    <select name="document_type" id="document_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm">
                                        <option value="Invoice">ใบแจ้งหนี้ (Invoice)</option>
                                        <option value="Receipt">ใบเสร็จรับเงิน (Receipt)</option>
                                        <option value="TaxInvoice">ใบกำกับภาษี (Tax Invoice)</option>
                                        <option value="AbbrevInvoice">ใบกำกับภาษีอย่างย่อ (Abbrev)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2">วันที่ออกเอกสาร <span class="text-rose-500">*</span></label>
                                    <input type="date" name="created_date" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2">เลขที่อ้างอิง</label>
                                    <input type="text" name="reference_number" placeholder="เช่น เลขที่ใบแจ้งหนี้เดิม" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm placeholder-slate-400">
                                </div>
                            </div>
                        </div>

                        <!-- Customer Search -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm p-6 transition-colors duration-300">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6"><i class="fa-solid fa-user mr-2 text-indigo-500"></i>ข้อมูลลูกค้า</h2>
                            <input type="hidden" name="customer_id" id="customer_id" required>
                            <div class="relative">
                                <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-2">ค้นหาลูกค้า (ชื่อ หรือ เลขผู้เสียภาษี) <span class="text-rose-500">*</span></label>
                                <input type="text" id="customer_search" placeholder="พิมพ์ชื่อหรือเลขผู้เสียภาษีเพื่อค้นหา..." autocomplete="off" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm placeholder-slate-400">
                                <ul id="customer_results" class="autocomplete-list hidden bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl mt-1 shadow-lg"></ul>
                            </div>
                            <!-- Selected Customer Display -->
                            <div id="customer_display" class="hidden mt-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-xl p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white text-sm" id="disp_name"></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" id="disp_address"></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Tax ID: <span id="disp_tax_id" class="font-semibold"></span> | สาขา: <span id="disp_branch" class="font-semibold"></span></p>
                                    </div>
                                    <button type="button" onclick="clearCustomer()" class="text-rose-500 hover:text-rose-700 text-sm font-semibold"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm p-6 transition-colors duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white"><i class="fa-solid fa-list mr-2 text-indigo-500"></i>รายการสินค้า/บริการ</h2>
                                <button type="button" onclick="addItemRow()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition-all">
                                    <i class="fa-solid fa-plus mr-1"></i> เพิ่มรายการ
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full" id="items_table">
                                    <thead>
                                        <tr class="text-left">
                                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase w-8">#</th>
                                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">รายการ</th>
                                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase w-28 text-center">จำนวน</th>
                                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase w-36 text-right">ราคา/หน่วย</th>
                                            <th class="px-3 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase w-36 text-right">จำนวนเงิน</th>
                                            <th class="px-3 py-3 w-10"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items_body">
                                        <!-- Dynamic rows inserted by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Summary -->
                    <div class="space-y-8">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm p-6 transition-colors duration-300 sticky top-10">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6"><i class="fa-solid fa-calculator mr-2 text-indigo-500"></i>สรุปยอด</h2>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">รวมเป็นเงิน</span>
                                    <span class="font-semibold text-slate-900 dark:text-white" id="summary_total">0.00</span>
                                    <input type="hidden" name="total_amount" id="total_amount" value="0">
                                </div>
                                
                                <div class="flex justify-between items-center gap-3">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">ส่วนลด</span>
                                    <input type="number" name="discount_amount" id="discount_amount" value="0" step="0.01" min="0" onchange="recalculate()" class="w-32 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-right text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>

                                <hr class="border-slate-100 dark:border-slate-700">

                                <div class="flex justify-between items-center gap-3">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">VAT (%)</span>
                                    <select name="vat_rate" id="vat_rate" onchange="recalculate()" class="w-32 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-right text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                        <option value="0">ไม่คิด VAT</option>
                                        <option value="7" selected>7%</option>
                                    </select>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">ภาษีมูลค่าเพิ่ม</span>
                                    <span class="font-semibold text-slate-900 dark:text-white" id="summary_vat">0.00</span>
                                    <input type="hidden" name="vat_amount" id="vat_amount" value="0">
                                </div>

                                <hr class="border-slate-100 dark:border-slate-700">

                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">ยอดเงินสุทธิ</span>
                                    <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400" id="summary_net">0.00</span>
                                    <input type="hidden" name="net_amount" id="net_amount" value="0">
                                </div>

                                <hr class="border-slate-100 dark:border-slate-700">

                                <div class="flex justify-between items-center gap-3">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">หัก ณ ที่จ่าย (%)</span>
                                    <select name="wht_percentage" id="wht_percentage" onchange="recalculate()" class="w-32 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-right text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                                        <option value="0" selected>ไม่หัก</option>
                                        <option value="1">1%</option>
                                        <option value="2">2%</option>
                                        <option value="3">3%</option>
                                    </select>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-500 dark:text-slate-400">ยอดหัก ณ ที่จ่าย</span>
                                    <span class="font-semibold text-rose-500" id="summary_wht">0.00</span>
                                    <input type="hidden" name="wht_amount" id="wht_amount" value="0">
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit" class="w-full px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-md text-sm uppercase tracking-wider">
                                    <i class="fa-solid fa-floppy-disk mr-2"></i> บันทึกเอกสาร
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </main>
    </div>

    <script>
        // Theme Toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const htmlElement = document.documentElement;
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlElement.classList.add('dark'); themeIcon.classList.remove('fa-moon'); themeIcon.classList.add('fa-sun');
        } else {
            htmlElement.classList.remove('dark'); themeIcon.classList.remove('fa-sun'); themeIcon.classList.add('fa-moon');
        }
        themeToggleBtn.addEventListener('click', () => {
            if (htmlElement.classList.contains('dark')) {
                htmlElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); themeIcon.classList.remove('fa-sun'); themeIcon.classList.add('fa-moon');
            } else {
                htmlElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); themeIcon.classList.remove('fa-moon'); themeIcon.classList.add('fa-sun');
            }
        });

        // === Customer Autocomplete ===
        let searchTimeout;
        const searchInput = document.getElementById('customer_search');
        const resultsList = document.getElementById('customer_results');

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length < 2) { resultsList.classList.add('hidden'); return; }
            searchTimeout = setTimeout(() => {
                fetch('<?= base_url("api/customers/search") ?>?q=' + encodeURIComponent(query))
                    .then(r => r.json())
                    .then(data => {
                        resultsList.innerHTML = '';
                        if (data.length === 0) {
                            resultsList.innerHTML = '<li class="px-4 py-3 text-sm text-slate-400">ไม่พบข้อมูล</li>';
                        } else {
                            data.forEach(c => {
                                const li = document.createElement('li');
                                li.className = 'px-4 py-3 cursor-pointer text-sm text-slate-700 dark:text-slate-300 border-b border-slate-100 dark:border-slate-600 last:border-0';
                                li.innerHTML = '<span class="font-semibold">' + escHtml(c.name) + '</span><br><span class="text-xs text-slate-400">Tax ID: ' + escHtml(c.tax_id || '-') + ' | สาขา: ' + escHtml(c.branch_code || '00000') + '</span>';
                                li.addEventListener('click', () => selectCustomer(c));
                                resultsList.appendChild(li);
                            });
                        }
                        resultsList.classList.remove('hidden');
                    });
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsList.contains(e.target)) resultsList.classList.add('hidden');
        });

        function selectCustomer(c) {
            document.getElementById('customer_id').value = c.id;
            document.getElementById('disp_name').textContent = c.name;
            document.getElementById('disp_address').textContent = c.address || '-';
            document.getElementById('disp_tax_id').textContent = c.tax_id || '-';
            document.getElementById('disp_branch').textContent = c.branch_code || '00000';
            document.getElementById('customer_display').classList.remove('hidden');
            searchInput.value = c.name;
            resultsList.classList.add('hidden');
        }

        function clearCustomer() {
            document.getElementById('customer_id').value = '';
            document.getElementById('customer_display').classList.add('hidden');
            searchInput.value = '';
        }

        function escHtml(str) {
            const div = document.createElement('div'); div.textContent = str; return div.innerHTML;
        }

        // === Dynamic Item Rows ===
        let rowIndex = 0;

        function addItemRow() {
            rowIndex++;
            const tbody = document.getElementById('items_body');
            const tr = document.createElement('tr');
            tr.id = 'item_row_' + rowIndex;
            tr.className = 'border-t border-slate-100 dark:border-slate-700';
            tr.innerHTML = `
                <td class="px-3 py-3 text-sm text-slate-400">${rowIndex}</td>
                <td class="px-3 py-3">
                    <input type="text" name="items[${rowIndex}][product_name]" required placeholder="ชื่อสินค้า/บริการ" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none placeholder-slate-400">
                </td>
                <td class="px-3 py-3">
                    <input type="number" name="items[${rowIndex}][quantity]" required value="1" min="0.01" step="0.01" onchange="calcRow(${rowIndex})" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm text-center focus:ring-2 focus:ring-indigo-500 outline-none" id="qty_${rowIndex}">
                </td>
                <td class="px-3 py-3">
                    <input type="number" name="items[${rowIndex}][unit_price]" required value="0" min="0" step="0.01" onchange="calcRow(${rowIndex})" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm text-right focus:ring-2 focus:ring-indigo-500 outline-none" id="price_${rowIndex}">
                </td>
                <td class="px-3 py-3 text-right">
                    <span class="text-sm font-semibold text-slate-900 dark:text-white" id="amt_display_${rowIndex}">0.00</span>
                    <input type="hidden" name="items[${rowIndex}][amount]" id="amt_${rowIndex}" value="0">
                </td>
                <td class="px-3 py-3 text-center">
                    <button type="button" onclick="removeRow(${rowIndex})" class="text-rose-400 hover:text-rose-600 transition-colors"><i class="fa-solid fa-trash-can text-sm"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
        }

        function calcRow(idx) {
            const qty = parseFloat(document.getElementById('qty_' + idx)?.value) || 0;
            const price = parseFloat(document.getElementById('price_' + idx)?.value) || 0;
            const amount = qty * price;
            const amtField = document.getElementById('amt_' + idx);
            const amtDisplay = document.getElementById('amt_display_' + idx);
            if (amtField) amtField.value = amount.toFixed(2);
            if (amtDisplay) amtDisplay.textContent = amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            recalculate();
        }

        function removeRow(idx) {
            const row = document.getElementById('item_row_' + idx);
            if (row) row.remove();
            recalculate();
        }

        function recalculate() {
            let total = 0;
            document.querySelectorAll('[id^="amt_"]').forEach(el => {
                if (el.type === 'hidden') total += parseFloat(el.value) || 0;
            });

            const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
            const vatRate = parseFloat(document.getElementById('vat_rate').value) || 0;
            const whtPct = parseFloat(document.getElementById('wht_percentage').value) || 0;

            const afterDiscount = total - discount;
            const vatAmount = afterDiscount * (vatRate / 100);
            const netAmount = afterDiscount + vatAmount;
            const whtAmount = afterDiscount * (whtPct / 100);

            document.getElementById('total_amount').value = total.toFixed(2);
            document.getElementById('summary_total').textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            document.getElementById('vat_amount').value = vatAmount.toFixed(2);
            document.getElementById('summary_vat').textContent = vatAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            document.getElementById('net_amount').value = netAmount.toFixed(2);
            document.getElementById('summary_net').textContent = netAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

            document.getElementById('wht_amount').value = whtAmount.toFixed(2);
            document.getElementById('summary_wht').textContent = whtAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        // เริ่มต้นด้วย 1 แถว
        addItemRow();
    </script>
</body>
</html>
