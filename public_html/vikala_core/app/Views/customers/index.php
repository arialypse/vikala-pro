<!DOCTYPE html>
<html lang="th" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vikala Pro - จัดการลูกค้า</title>
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
    </style>
</head>
<body class="flex min-h-screen text-slate-700 dark:text-slate-300 antialiased">

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
                <a href="<?= base_url('customers') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all sidebar-active text-left">
                    <i class="fa-solid fa-users w-5 text-center"></i> Customers
                </a>
                <a href="<?= base_url('products') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-left">
                    <i class="fa-solid fa-box w-5 text-center"></i> Products
                </a>
                <a href="<?= base_url('documents') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-left">
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
        
        <header class="h-20 bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between px-10 shrink-0 transition-colors duration-300">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight uppercase">Customers</h1>
            <div class="flex items-center gap-4">
                <button onclick="openCustomerModal()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                    <i class="fa-solid fa-plus mr-2"></i>เพิ่มลูกค้าใหม่
                </button>
                <button id="theme-toggle" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-all relative">
                    <i id="theme-icon" class="fa-solid fa-moon"></i>
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 space-y-8 dark:bg-slate-900 transition-colors duration-300">
            
            <?php if(session()->getFlashdata('success')): ?>
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-6 py-4 rounded-2xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span class="font-medium"><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            <?php endif; ?>
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

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">รายชื่อลูกค้าส่วนกลาง (Global Customers)</h2>
                        <p class="text-sm text-slate-400 mt-1">รายชื่อเหล่านี้ถูกแชร์ร่วมกันทุกบริษัทในระบบ</p>
                    </div>
                    <span class="bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1.5 rounded-lg"><?= count($customers ?? []) ?> รายการ</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700/50 text-left">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">ชื่อนิติบุคคล / ชื่อลูกค้า</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">เลขผู้เสียภาษี (Tax ID)</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">รหัสสาขา</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">ที่อยู่</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            <?php if(!empty($customers)): ?>
                                <?php foreach($customers as $cus): ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-slate-900 dark:text-white"><?= esc($cus['name']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 font-mono">
                                        <?= esc($cus['tax_id']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        <?= esc($cus['branch_code'] ?? '00000') ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 max-w-xs truncate" title="<?= esc($cus['address']) ?>">
                                        <?= esc($cus['address']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" 
                                                onclick="openCustomerModal(<?= $cus['id'] ?>, '<?= esc(addslashes($cus['name'])) ?>', '<?= esc(addslashes($cus['tax_id'])) ?>', '<?= esc(addslashes($cus['branch_code'])) ?>', '<?= esc(addslashes($cus['address'])) ?>')" 
                                                class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center hover:bg-amber-100 dark:hover:bg-amber-800 transition-all" title="แก้ไข">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                            <a href="<?= base_url('customers/disable/' . $cus['id']) ?>" onclick="return confirm('คุณต้องการซ่อนข้อมูลลูกค้ารายนี้หรือไม่? (เอกสารเก่าจะไม่ได้รับผลกระทบ)')" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center hover:bg-rose-100 hover:text-rose-600 dark:hover:bg-rose-900/50 dark:hover:text-rose-400 transition-all" title="ซ่อน/ปิดการใช้งาน">
                                                <i class="fa-solid fa-eye-slash text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 text-2xl">
                                                <i class="fa-solid fa-users-slash"></i>
                                            </div>
                                            <p class="text-slate-500 dark:text-slate-400 font-medium">ยังไม่มีข้อมูลลูกค้าในระบบ</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="customerModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity">
        <div class="bg-white dark:bg-slate-800 w-full max-w-lg p-8 rounded-3xl shadow-xl transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 id="modalTitle" class="font-bold text-slate-900 dark:text-white text-xl">เพิ่มลูกค้าใหม่</h3>
                <button onclick="closeCustomerModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg transition-colors"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form id="customerForm" action="<?= base_url('customers/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-1">ชื่อนิติบุคคล / ชื่อลูกค้า <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="cus_name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-1">เลขผู้เสียภาษี (Tax ID) <span class="text-rose-500">*</span></label>
                        <input type="text" name="tax_id" id="cus_tax_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-1">รหัสสาขา (5 หลัก)</label>
                        <input type="text" name="branch_code" id="cus_branch" value="00000" placeholder="00000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-600 dark:text-slate-400 mb-1">ที่อยู่ <span class="text-rose-500">*</span></label>
                    <textarea name="address" id="cus_address" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm"></textarea>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeCustomerModal()" class="flex-1 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 transition-all">ยกเลิก</button>
                    <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md transition-all">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Theme Toggle Logic
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

        // Modal Logic
        function openCustomerModal(id = null, name = '', taxId = '', branch = '00000', address = '') {
            const modal = document.getElementById('customerModal');
            const form = document.getElementById('customerForm');
            const title = document.getElementById('modalTitle');
            
            // ถ้ารับ ID มา แปลว่าเป็นการกดแก้ไข (Edit)
            if(id) {
                title.textContent = 'แก้ไขข้อมูลลูกค้า';
                form.action = '<?= base_url('customers/update/') ?>' + id;
                document.getElementById('cus_name').value = name;
                document.getElementById('cus_tax_id').value = taxId;
                document.getElementById('cus_branch').value = branch;
                document.getElementById('cus_address').value = address;
            } else {
                title.textContent = 'เพิ่มลูกค้าใหม่';
                form.action = '<?= base_url('customers/store') ?>';
                form.reset();
                document.getElementById('cus_branch').value = '00000'; // Default สำนักงานใหญ่
            }
            
            modal.classList.remove('hidden');
        }

        function closeCustomerModal() {
            document.getElementById('customerModal').classList.add('hidden');
        }
    </script>
</body>
</html>