@php
    // Data topik didefinisikan di sisi server (Blade) supaya nama ikon Heroicon
    // bisa dioper langsung ke komponen resmi <x-filament::icon icon="heroicon-o-...">.
    // Alpine hanya memakai salinan JSON dari data ini untuk pencarian/filter/scrollspy.
    $topics = [
        [
            'id' => 'attendance',
            'title' => 'Absensi & Attendance Reports',
            'description' => 'Lihat laporan kehadiran karyawan, periode attendance, dan hasil rekonsiliasi.',
            'menu' => 'Attendance Reports',
            'category' => 'operasional',
            'keywords' => ['attendance', 'absensi', 'kehadiran', 'laporan'],
            'icon' => 'heroicon-o-clock',
            'color' => 'blue',
        ],
        [
            'id' => 'leave',
            'title' => 'Cuti & Saldo Leave',
            'description' => 'Proses pengajuan cuti, approval, dan saldo cuti otomatis.',
            'menu' => 'Leave Requests',
            'category' => 'operasional',
            'keywords' => ['cuti', 'leave', 'saldo', 'approval'],
            'icon' => 'heroicon-o-calendar',
            'color' => 'emerald',
        ],
        [
            'id' => 'payroll',
            'title' => 'Payroll & Slip Gaji',
            'description' => 'Generate payroll dari attendance dan review payroll detail sebelum penggajian.',
            'menu' => 'Payrolls',
            'category' => 'keuangan',
            'keywords' => ['payroll', 'gaji', 'slip', 'kompensasi'],
            'icon' => 'heroicon-o-currency-dollar',
            'color' => 'amber',
        ],
        [
            'id' => 'loan',
            'title' => 'Pinjaman Karyawan',
            'description' => 'Kelola loan, instalment, dan status pembayaran karyawan.',
            'menu' => 'Employee Loans',
            'category' => 'keuangan',
            'keywords' => ['loan', 'pinjaman', 'angsuran', 'kasbon'],
            'icon' => 'heroicon-o-banknotes',
            'color' => 'purple',
        ],
        [
            'id' => 'schedule',
            'title' => 'Jadwal Libur Karyawan',
            'description' => 'Atur Work Schedule dan rotasi shift untuk tim HR dan operasional.',
            'menu' => 'Work Schedule',
            'category' => 'operasional',
            'keywords' => ['jadwal', 'schedule', 'shift', 'work schedule'],
            'icon' => 'heroicon-o-calendar-days',
            'color' => 'sky',
        ],
        [
            'id' => 'users',
            'title' => 'Manajemen User & Role',
            'description' => 'Kontrol akses dengan role, permissions, dan grup user HRIS.',
            'menu' => 'Users & Settings',
            'category' => 'administrasi',
            'keywords' => ['user', 'role', 'akses', 'permissions'],
            'icon' => 'heroicon-o-users',
            'color' => 'rose',
        ],
        [
            'id' => 'security',
            'title' => 'Keamanan & Akses',
            'description' => 'Terapkan aturan peran Spatie untuk operasi sensitif dan audit akses.',
            'menu' => 'Settings',
            'category' => 'administrasi',
            'keywords' => ['keamanan', 'security', 'akses', 'role'],
            'icon' => 'heroicon-o-shield-check',
            'color' => 'slate',
        ],
    ];

    $topicColorClasses = [
        'blue' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300',
        'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300',
        'amber' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300',
        'purple' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-300',
        'sky' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300',
        'rose' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300',
        'slate' => 'bg-slate-100 text-slate-600 dark:bg-slate-500/10 dark:text-slate-300',
    ];

    $categories = [
        ['id' => 'all', 'label' => 'Semua'],
        ['id' => 'operasional', 'label' => 'Operasional'],
        ['id' => 'keuangan', 'label' => 'Keuangan'],
        ['id' => 'administrasi', 'label' => 'Administrasi'],
    ];

    $steps = [
        ['title' => 'Verifikasi setup HRIS', 'description' => 'Susun peran, akses, approval, dan struktur organisasi sebelum masuk ke modul utama.'],
        ['title' => 'Kelola absensi dan jadwal', 'description' => 'Atur Attendance, Work Schedule, dan laporan kehadiran sebelum penggajian.'],
        ['title' => 'Proses payroll bulanan', 'description' => 'Jalankan payroll dari attendance report, tinjau deductions, dan unduh slip gaji.'],
        ['title' => 'Pantau pinjaman', 'description' => 'Kelola Employee Loans dan Loan Installments untuk mengawasi cicilan karyawan.'],
        ['title' => 'Audit kebijakan & dukungan', 'description' => 'Tinjau role admin, keamanan data, dan use case help center untuk pengguna HR.'],
    ];

    $faqItems = [
        ['id' => 'faq-1', 'question' => 'Bagaimana cara memulai payroll pertama?', 'answer' => 'Mulai dari Attendance Reports, pilih periode yang aktif, generate payroll, lalu verifikasi payroll details dan download melalui menu Payrolls.', 'open' => true],
        ['id' => 'faq-2', 'question' => 'Di mana saya memantau status pinjaman karyawan?', 'answer' => 'Buka Employee Loans untuk melihat pinjaman, lalu gunakan Loan Installments jika perlu mencatat pembayaran cicilan.', 'open' => false],
        ['id' => 'faq-3', 'question' => 'Bagaimana mengatur hak akses admin?', 'answer' => 'Gunakan role admin / super_admin di menu Settings, lalu pastikan user terkait memiliki permission yang benar untuk modul sensitif.', 'open' => false],
        ['id' => 'faq-4', 'question' => 'Apa yang harus dilakukan jika data attendance tidak sesuai?', 'answer' => 'Periksa kembali Work Schedule, perbaiki entri attendance, lalu jalankan ulang laporan di Attendance Reports sebelum memproses payroll.', 'open' => false],
    ];
@endphp

<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<x-filament-panels::page>
    <div
        x-data="{
            query: '',
            activeCategory: 'all',
            currentStep: 1,
            activeTopic: null,
            copiedId: null,
            showBackToTop: false,

            topics: @js($topics),
            categories: @js($categories),
            steps: @js($steps),
            faq: @js($faqItems),

            get filteredTopics() {
                const q = this.query.trim().toLowerCase();
                return this.topics.filter((topic) => {
                    const matchesCategory = this.activeCategory === 'all' || topic.category === this.activeCategory;
                    const matchesQuery = ! q
                        || topic.title.toLowerCase().includes(q)
                        || topic.description.toLowerCase().includes(q)
                        || topic.keywords.some((word) => word.includes(q));
                    return matchesCategory && matchesQuery;
                });
            },

            isVisible(id) {
                return this.filteredTopics.some((topic) => topic.id === id);
            },

            get progressValue() {
                return Math.round((this.currentStep / this.steps.length) * 100);
            },

            goToTopic(id) {
                this.query = '';
                this.activeCategory = 'all';
                this.$nextTick(() => {
                    const el = document.getElementById(id);
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            },

            copyLink(id) {
                const url = window.location.href.split('#')[0] + '#' + id;
                navigator.clipboard?.writeText(url);
                this.copiedId = id;
                setTimeout(() => { if (this.copiedId === id) this.copiedId = null; }, 1500);
            },

            scrollToTop() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            initScrollSpy() {
                const sections = this.topics.map((t) => document.getElementById(t.id)).filter(Boolean);
                if (! sections.length) return;
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) this.activeTopic = entry.target.id;
                    });
                }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });
                sections.forEach((section) => observer.observe(section));
                window.addEventListener('scroll', () => {
                    this.showBackToTop = window.scrollY > 400;
                });
            },
        }"
        x-init="initScrollSpy()"
        class="space-y-6 scroll-smooth"
    >
        {{-- ===================== HERO ===================== --}}
        <div class="relative overflow-hidden rounded-3xl border border-gray-200 bg-gradient-to-br from-primary-600 via-primary-600 to-indigo-700 p-6 shadow-sm sm:p-10">
            <div class="pointer-events-none absolute -right-14 -top-14 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-1/4 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                        Pusat Bantuan HRIS
                    </span>
                    <h1 class="mt-3 text-3xl font-bold text-white sm:text-4xl">Panduan Penggunaan</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-white/85">Semua yang perlu Anda tahu tentang Attendance, Leave, Payroll, Pinjaman, Jadwal Libur Karyawan, Pengguna, dan Pengaturan &mdash; dalam satu tempat.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 lg:w-72" type="button">
                    <button id="#attendance" @click.prevent="goToTopic('attendance')" class="rounded-full inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-primary-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-primary-50">
                        Buka Absensi
                    </button>
                    <a id="#payroll" @click.prevent="goToTopic('payroll')" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/40 bg-white/10 px-4 py-3 text-sm font-semibold text-white shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20">
                        Buka Payroll
                    </a>
                </div>
            </div>

            {{-- Search + kategori --}}
            <div class="relative mt-7 space-y-3">
                <div class="relative max-w-xl">
                    <x-filament::icon icon="heroicon-o-magnifying-glass" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <input
                        type="search"
                        x-model.debounce.200="query"
                        placeholder="Cari topik, misalnya: payroll, cuti, pinjaman..."
                        class="w-full rounded-xl border-0 bg-white py-3 pl-10 pr-4 text-sm text-gray-900 shadow-lg ring-1 ring-black/5 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-white"
                    />
                </div>

                <div class="flex flex-wrap gap-2">
                    <template x-for="category in categories" :key="category.id">
                        <button
                            type="button"
                            @click="activeCategory = category.id"
                            class="rounded-full px-3.5 py-1.5 text-xs font-semibold transition"
                            :class="activeCategory === category.id
                                ? 'bg-white text-primary-700 shadow-sm'
                                : 'bg-white/10 text-white hover:bg-white/20'"
                            x-text="category.label"
                        ></button>
                    </template>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="space-y-6">
                {{-- ===================== TOPIK BANTUAN (SEARCHABLE GRID) ===================== --}}
                <x-filament::card>
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Topik Bantuan</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Klik kartu untuk langsung menuju panduan lengkap.</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300" x-text="filteredTopics.length + ' topik'"></span>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($topics as $topic)
                            <button
                                type="button"
                                @click="goToTopic('{{ $topic['id'] }}')"
                                x-show="isVisible('{{ $topic['id'] }}')"
                                x-cloak
                                class="group flex items-start gap-3 rounded-2xl border border-gray-200 bg-white p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-primary-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
                            >
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $topicColorClasses[$topic['color']] }}">
                                    <x-filament::icon :icon="$topic['icon']" class="h-5 w-5" />
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $topic['title'] }}</span>
                                    <span class="mt-0.5 block text-xs leading-5 text-gray-500 dark:text-gray-400">{{ $topic['description'] }}</span>
                                    <span class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-primary-600 opacity-0 transition group-hover:opacity-100 dark:text-primary-400">
                                        Lihat panduan
                                        <x-filament::icon icon="heroicon-o-arrow-right" class="h-3.5 w-3.5" />
                                    </span>
                                </span>
                            </button>
                        @endforeach
                    </div>

                    <div x-show="! filteredTopics.length" x-cloak class="mt-4 rounded-2xl border border-dashed border-gray-300 bg-white p-6 text-center text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        Tidak ada topik yang cocok. Coba kata kunci atau kategori lain.
                    </div>
                </x-filament::card>

                {{-- ===================== MEDIA BANTUAN ===================== --}}
                <x-filament::card>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Media Bantuan</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Gambar dan video pendukung untuk mempercepat pemahaman modul HRIS.</p>
                            </div>
                            <span class="rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 dark:bg-slate-900/80 dark:text-slate-200">Visual</span>
                        </div>
                        <div class="grid gap-4 lg:grid-cols-[1.3fr_0.7fr]">
                            <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-950">
                                    <img src="https://via.placeholder.com/640x360?text=Screenshot+HRIS" alt="Screenshot HRIS" class="h-48 w-full object-cover" loading="lazy" />
                                </div>
                                <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Contoh tampilan modul payroll dan laporan absensi untuk karyawan.</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                                <div class="aspect-video overflow-hidden rounded-2xl border border-gray-200 bg-gray-950 dark:border-gray-700">
                                    <video controls class="h-full w-full object-cover">
                                        <source src="" type="video/mp4" />
                                        Peramban Anda tidak mendukung tag video.
                                    </video>
                                </div>
                                <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Video ringkas: langkah proses penggajian dan approval cuti.</p>
                            </div>
                        </div>
                    </div>
                </x-filament::card>

                {{-- ===================== FAQ (ACCORDION TANPA PLUGIN TAMBAHAN) ===================== --}}
                <x-filament::card>
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Pertanyaan yang Sering Diajukan</h2>
                        <div class="space-y-3">
                            <template x-for="item in faq" :key="item.id">
                                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                                    <button
                                        type="button"
                                        @click="item.open = ! item.open"
                                        class="flex w-full items-center justify-between gap-3 px-4 py-3.5 text-left"
                                    >
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="item.question"></span>
                                        <x-filament::icon icon="heroicon-o-chevron-down" class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" x-bind:class="item.open ? 'rotate-180' : ''" />
                                    </button>
                                    <div
                                        x-show="item.open"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="border-t border-gray-100 px-4 py-3.5 text-sm leading-6 text-gray-600 dark:border-gray-800 dark:text-gray-300"
                                        x-text="item.answer"
                                    ></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </x-filament::card>
            </div>

            {{-- ===================== SIDEBAR ===================== --}}
            <aside class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                <x-filament::card>
                    <div class="space-y-3">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Daftar Isi</h2>
                        <ul class="space-y-1">
                            @foreach ($topics as $topic)
                                <li>
                                    <a
                                        href="#{{ $topic['id'] }}"
                                        @click.prevent="goToTopic('{{ $topic['id'] }}')"
                                        class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition"
                                        :class="activeTopic === '{{ $topic['id'] }}'
                                            ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-300'
                                            : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800'"
                                    >
                                        <x-filament::icon :icon="$topic['icon']" class="h-4 w-4 shrink-0" />
                                        <span class="flex-1">{{ $topic['title'] }}</span>
                                        <span x-show="activeTopic === '{{ $topic['id'] }}'" x-cloak class="h-1.5 w-1.5 rounded-full bg-primary-500"></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Wizard Langkah</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Ikuti urutan modul HRIS untuk implementasi yang lebih cepat.</p>
                            </div>
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:bg-blue-500/10 dark:text-blue-300" x-text="'Langkah ' + currentStep + ' / ' + steps.length"></span>
                        </div>

                        <div class="space-y-2">
                            <template x-for="(step, index) in steps" :key="step.title">
                                <button
                                    type="button"
                                    @click="currentStep = index + 1"
                                    class="w-full rounded-2xl border p-4 text-left transition"
                                    :class="index + 1 === currentStep
                                        ? 'border-primary-500 bg-primary-50 dark:bg-primary-500/10'
                                        : 'border-gray-200 bg-white hover:border-primary-200 dark:border-gray-700 dark:bg-gray-900'"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-semibold shadow-sm"
                                            :class="index + 1 <= currentStep
                                                ? 'bg-primary-600 text-white'
                                                : 'bg-white text-gray-500 dark:bg-gray-950 dark:text-gray-400'"
                                        >
                                            <template x-if="index + 1 < currentStep">
                                                <x-filament::icon icon="heroicon-o-check" class="h-4 w-4" />
                                            </template>
                                            <template x-if="index + 1 >= currentStep">
                                                <span x-text="index + 1"></span>
                                            </template>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="step.title"></p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400" x-text="step.description"></p>
                                        </div>
                                    </div>
                                </button>
                            </template>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="button" :disabled="currentStep === 1" @click="currentStep = Math.max(1, currentStep - 1)" class="inline-flex flex-1 items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200">Sebelumnya</button>
                            <button type="button" :disabled="currentStep === steps.length" @click="currentStep = Math.min(steps.length, currentStep + 1)" class="inline-flex flex-1 items-center justify-center rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-40">Berikutnya</button>
                        </div>
                    </div>
                </x-filament::card>

                <x-filament::card>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Progress Penggunaan</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Lihat progress langkah implementasi HRIS Anda.</p>
                            </div>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200" x-text="progressValue + '%'"></span>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-full rounded-full bg-primary-600 transition-all duration-300" :style="`width: ${progressValue}%`"></div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <p class="font-semibold">Modul utama yang direkomendasikan:</p>
                            <ul class="mt-3 space-y-2 text-sm">
                                <li>• Attendance</li>
                                <li>• Leave</li>
                                <li>• Payroll</li>
                                <li>• Loans</li>
                                <li>• Settings</li>
                            </ul>
                        </div>
                    </div>
                </x-filament::card>
            </aside>
        </div>

        {{-- ===================== KUMPULAN PANDUAN UTAMA (DETAIL PER TOPIK) ===================== --}}
        <div class="space-y-6">
            <x-filament::section heading="Kumpulan Panduan Utama">
                <div class="grid gap-6">
                    @foreach ($topics as $topic)
                        <section
                            id="{{ $topic['id'] }}"
                            class="scroll-mt-24 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm transition dark:border-gray-700 dark:bg-gray-900"
                            :class="activeTopic === '{{ $topic['id'] }}' ? 'ring-2 ring-primary-400/60' : ''"
                        >
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $topicColorClasses[$topic['color']] }}">
                                        <x-filament::icon :icon="$topic['icon']" class="h-5 w-5" />
                                    </span>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-950 dark:text-white">{{ $topic['title'] }}</h3>
                                        <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-400">{{ $topic['description'] }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ $topic['menu'] }}</span>
                                    <button
                                        type="button"
                                        @click="copyLink('{{ $topic['id'] }}')"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    >
                                        <x-filament::icon icon="heroicon-o-link" class="h-3.5 w-3.5" />
                                        <span x-text="copiedId === '{{ $topic['id'] }}' ? 'Tersalin!' : 'Salin tautan'"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-950">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Langkah Cepat</p>
                                    <ol class="mt-3 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                                        <li>1. Pilih modul <span class="font-medium">{{ $topic['menu'] }}</span></li>
                                        <li>2. Isi data utama</li>
                                        <li>3. Verifikasi dan simpan</li>
                                    </ol>
                                </div>
                                <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Tingkatkan efisiensi</p>
                                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Gunakan help center ini untuk mengurangi waktu pelatihan dan mempercepat proses HRIS end-to-end.</p>
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>
            </x-filament::section>
        </div>

        {{-- ===================== BACK TO TOP ===================== --}}
        <button
            type="button"
            x-show="showBackToTop"
            x-cloak
            x-transition
            @click="scrollToTop()"
            class="fixed bottom-6 right-6 z-40 flex h-11 w-11 items-center justify-center rounded-full bg-primary-600 text-white shadow-lg transition hover:bg-primary-700"
            aria-label="Kembali ke atas"
        >
            <x-filament::icon icon="heroicon-o-arrow-up" class="h-5 w-5" />
        </button>
    </div>
</x-filament-panels::page>