<!DOCTYPE html>
<html lang="th" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vikala Pro - <?= esc($title ?? 'Dashboard') ?></title>
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
        .dark body { background-color: #0f172a; } /* slate-900 */
        .sidebar-active { background-color: #EEF2FF; color: #4F46E5; border-right: 4px solid #4F46E5; }
        .dark .sidebar-active { background-color: #1e1b4b; color: #818cf8; border-right: 4px solid #818cf8; } /* indigo-950, indigo-400 */
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
                <a href="<?= base_url('dashboard') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all sidebar-active text-left">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard
                </a>
                <a href="<?= base_url('customers') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-left">
                    <i class="fa-solid fa-users w-5 text-center"></i> Customers
                </a>
                <a href="<?= base_url('products') ?>" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-left">
                    <i class="fa-solid fa-box w-5 text-center"></i> Products
                </a>
                <a href="#" class="w-full flex items-center gap-4 px-4 py-3 text-slate-500 dark:text-slate-400 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-left">
                    <i class="fa-solid fa-file-invoice w-5 text-center"></i> Documents
                </a>
            </nav>
        </div>

        <div>
            <div class="bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 rounded-2xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-lg shrink-0"><i class="fa-solid fa-user-tie"></i></div>
                    <div>
                        <h4 class="font-semibold text-slate-900 dark:text-white text-sm mb-1"><?= esc($fullname) ?></h4>
                        <p class="text-xs text-indigo-900/80 dark:text-indigo-200/80 leading-relaxed mb-2">Role: <?= esc($role) ?></p>
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
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight uppercase"><?= esc($title ?? 'Dashboard') ?></h1>
            <div class="flex items-center gap-4">
                <?php if($role === 'Admin'): ?>
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 text-xs font-bold rounded-lg uppercase">Admin Mode</span>
                <?php endif; ?>
                
                <button id="theme-toggle" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-all relative">
                    <i id="theme-icon" class="fa-solid fa-moon"></i>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-10 space-y-8 dark:bg-slate-900 transition-colors duration-300">
            
            <section class="space-y-8">
                <!-- Welcome Message -->
                <div class="bg-gradient-to-r from-indigo-600 to-violet-700 p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
                    <div class="absolute right-0 bottom-0 top-0 w-1/3 opacity-10 flex items-center justify-center text-[10rem]"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="max-w-xl relative z-10 space-y-3">
                        <span class="bg-white/20 text-white font-bold text-xs px-3 py-1 rounded-full uppercase tracking-wider">Welcome Back</span>
                        <h2 class="text-3xl font-bold tracking-tight">Hello, <?= esc($fullname) ?>!</h2>
                        <p class="text-indigo-100/90 text-sm leading-relaxed">Here is an overview of your active company's performance. Navigate through the menu to manage customers, products, and issue new documents.</p>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm flex justify-between items-start transition-colors duration-300">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Total Invoices</span>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">0</h3>
                            <span class="text-xs text-slate-400 block mt-1">This month</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400"><i class="fa-solid fa-file-invoice"></i></div>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm flex justify-between items-start transition-colors duration-300">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Total Receipts</span>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">0</h3>
                            <span class="text-xs text-slate-400 block mt-1">This month</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400"><i class="fa-solid fa-receipt"></i></div>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm flex justify-between items-start transition-colors duration-300">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Active Customers</span>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">0</h3>
                            <span class="text-xs text-slate-400 block mt-1">In global database</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400"><i class="fa-solid fa-users"></i></div>
                    </div>
                </div>
            </section>
            
        </main>
    </div>

    <script>
        // Theme Toggle Logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const htmlElement = document.documentElement;

        // Check local storage or system preference on load
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
