<!DOCTYPE html>
<html lang="th" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vikala Pro - เอกสาร</title>
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
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight uppercase">Documents</h1>
            <div class="flex items-center gap-4">
                <a href="<?= base_url('documents/create') ?>" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                    <i class="fa-solid fa-plus mr-2"></i>ออกเอกสารใหม่
                </a>
                <button id="theme-toggle" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-all relative">
                    <i id="theme-icon" class="fa-solid fa-moon"></i>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-10 space-y-8 dark:bg-slate-900 transition-colors duration-300">
            
            <!-- Flash Messages -->
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

            <!-- Documents Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-300">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">รายการเอกสาร</h2>
                        <p class="text-sm text-slate-400 mt-1">เอกสารทั้งหมดของบริษัทที่เลือก</p>
                    </div>
                    <span class="bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 text-xs font-bold px-3 py-1.5 rounded-lg"><?= count($documents ?? []) ?> รายการ</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700/50 text-left">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">เลขที่เอกสาร</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">ประเภท</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">ลูกค้า</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">วันที่</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">ยอดสุทธิ</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">สถานะ</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            <?php if(!empty($documents)): ?>
                                <?php foreach($documents as $doc): ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-slate-900 dark:text-white"><?= esc($doc['document_number']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                            $typeBadge = match($doc['document_type']) {
                                                'Invoice'       => ['ใบแจ้งหนี้', 'bg-blue-50 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300'],
                                                'Receipt'       => ['ใบเสร็จรับเงิน', 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'],
                                                'TaxInvoice'    => ['ใบกำกับภาษี', 'bg-violet-50 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300'],
                                                'AbbrevInvoice' => ['ใบกำกับภาษีอย่างย่อ', 'bg-amber-50 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300'],
                                                default         => [$doc['document_type'], 'bg-slate-50 text-slate-700 dark:bg-slate-700 dark:text-slate-300']
                                            };
                                        ?>
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg <?= $typeBadge[1] ?>"><?= $typeBadge[0] ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400"><?= esc($doc['customer_name']) ?></td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400"><?= date('d/m/Y', strtotime($doc['created_date'])) ?></td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white text-right"><?= number_format($doc['net_amount'], 2) ?></td>
                                    <td class="px-6 py-4">
                                        <?php if($doc['status'] === 'Active'): ?>
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">Active</span>
                                        <?php elseif($doc['status'] === 'Trash'): ?>
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300">Trash</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="<?= base_url('documents/print/' . $doc['id']) ?>" target="_blank" class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center hover:bg-indigo-100 dark:hover:bg-indigo-800 transition-all" title="พิมพ์ PDF">
                                                <i class="fa-solid fa-print text-sm"></i>
                                            </a>
                                            <?php if($doc['status'] === 'Active'): ?>
                                            <a href="<?= base_url('documents/trash/' . $doc['id']) ?>" onclick="return confirm('คุณต้องการย้ายเอกสารนี้ไปถังขยะหรือไม่?')" class="w-9 h-9 rounded-lg bg-rose-50 dark:bg-rose-900/50 text-rose-500 dark:text-rose-400 flex items-center justify-center hover:bg-rose-100 dark:hover:bg-rose-800 transition-all" title="ย้ายลงถังขยะ">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 text-2xl">
                                                <i class="fa-solid fa-file-circle-plus"></i>
                                            </div>
                                            <p class="text-slate-500 dark:text-slate-400 font-medium">ยังไม่มีเอกสาร</p>
                                            <a href="<?= base_url('documents/create') ?>" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-all">
                                                <i class="fa-solid fa-plus mr-1"></i> ออกเอกสารใบแรก
                                            </a>
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

    <script>
        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const htmlElement = document.documentElement;

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlElement.classList.add('dark');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else {
            htmlElement.classList.remove('dark');
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }

        themeToggleBtn.addEventListener('click', () => {
            if (htmlElement.classList.contains('dark')) {
                htmlElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                htmlElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        });
    </script>
</body>
</html>
