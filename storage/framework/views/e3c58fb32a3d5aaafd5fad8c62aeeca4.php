<div class="bg-white min-h-screen pb-20 font-[Inter]">
    <!-- Breadcrumb & Judul -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
        <nav class="flex text-sm text-[#6b7280] mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-[#1967d2] transition-colors">Beranda</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="<?php echo e(route('cari')); ?>" class="hover:text-[#1967d2] transition-colors ml-1 md:ml-2">Cari Kos</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-[#090a0b] font-medium md:ml-2"><?php echo e($properti->nama_properti); ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-[#090a0b] mb-2"><?php echo e($properti->nama_properti); ?></h1>
        <div class="flex items-center text-sm text-[#090a0b] gap-4">
            <div class="flex items-center font-semibold">
                <svg class="w-4 h-4 text-yellow-400 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                <?php echo e(number_format($properti->ulasan->avg('rating') ?? 0, 1)); ?> (<?php echo e($properti->ulasan->count()); ?> ulasan)
            </div>
            <div class="flex items-center underline">
                <?php echo e($properti->alamat_lengkap); ?>

            </div>
        </div>
    </div>

    <!-- Galeri Foto -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 rounded-2xl overflow-hidden h-[300px] md:h-[400px]">
            <!-- Foto Utama -->
            <?php
                $semuaFoto = $properti->getMediaDisplayUrls('foto_properti');
                $fotoUtama = $semuaFoto[0] ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&q=80&w=800';
            ?>
            <div class="w-full h-full relative group cursor-pointer">
                <img src="<?php echo e($fotoUtama); ?>" alt="Main Photo" class="w-full h-full object-cover group-hover:brightness-90 transition-all duration-300">
            </div>
            
            <!-- Grid 4 Foto Kecil -->
            <div class="hidden md:grid grid-cols-2 grid-rows-2 gap-2 w-full h-full">
                <?php
                    $dummies = [
                        'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&q=80&w=400',
                        'https://images.unsplash.com/photo-1502672260266-1c1de2d966ce?auto=format&fit=crop&q=80&w=400',
                        'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&q=80&w=400',
                        'https://images.unsplash.com/photo-1556020685-e631933645ce?auto=format&fit=crop&q=80&w=400'
                    ];
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 4; $i++): ?>
                    <div class="w-full h-full relative group cursor-pointer bg-slate-100">
                        <img src="<?php echo e($semuaFoto[$i + 1] ?? $dummies[$i]); ?>" alt="Photo <?php echo e($i+2); ?>" class="w-full h-full object-cover group-hover:brightness-90 transition-all duration-300">
                    </div>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <button class="absolute bottom-4 right-8 bg-white border border-[#090a0b] px-4 py-1.5 rounded-lg text-sm font-semibold shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2 z-10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Lihat Semua Foto
        </button>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Kolom Kiri (65%) -->
            <div class="lg:w-[65%]">
                
                <!-- Info Host & Label -->
                <div class="flex items-center justify-between pb-6 border-b border-gray-200">
                    <div>
                        <h2 class="text-xl font-bold text-[#090a0b] mb-1">
                            Disewakan oleh <?php echo e($properti->pemilikProperti->user->name ?? 'Mitra APPKONKOS'); ?>

                        </h2>
                        <div class="flex items-center gap-2 mt-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipe === 'kosan'): ?>
                                <span class="bg-[#e0f2fe] text-[#0369a1] text-xs font-semibold px-2.5 py-1 rounded-full border border-sky-100">Kos <?php echo e(ucfirst($properti->jenis_kos)); ?></span>
                            <?php else: ?>
                                <span class="bg-[#e0f2fe] text-[#0369a1] text-xs font-semibold px-2.5 py-1 rounded-full border border-sky-100">Kontrakan</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="text-sm text-[#6b7280] font-medium"><?php echo e($tipe === 'kosan' ? $properti->tipeKamar->count() . ' Tipe Kamar' : 'Sisa ' . $properti->sisa_kamar . ' Unit'); ?></span>
                        </div>
                    </div>
                    <img src="<?php echo e($properti->pemilikProperti->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($properti->pemilikProperti->user->name ?? 'Mitra').'&background=1967d2&color=fff'); ?>" alt="Avatar" class="w-14 h-14 rounded-full shadow-md ring-2 ring-white object-cover">
                </div>

                <!-- Fasilitas Utama (Icon bar) -->
                <div class="py-6 border-b border-gray-200">
                    <div class="flex flex-wrap gap-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                            <span class="text-[#090a0b] font-medium">WiFi Gratis</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                            <span class="text-[#090a0b] font-medium">AC & Kipas</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            <span class="text-[#090a0b] font-medium">Kasur Empuk</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-[#1967d2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span class="text-[#090a0b] font-medium">K. Mandi Dalam</span>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-[#090a0b] mb-4">Tentang properti ini</h3>
                    <p class="text-[#6b7280] leading-relaxed">
                        Properti ini merupakan pilihan ideal bagi Anda yang mencari kenyamanan dan aksesibilitas. Berlokasi strategis dengan lingkungan yang aman dan bersih. 
                        Tersedia berbagai fasilitas pendukung untuk memudahkan aktivitas harian Anda.
                    </p>
                </div>

                <!-- Fasilitas Lengkap -->
                <div class="py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-[#090a0b] mb-4">Fasilitas yang tersedia</h3>
                    <?php
                        $fasilitasArray = [];
                        if ($tipe === 'kosan' && $properti->tipeKamar->isNotEmpty()) {
                            $fasilitasStr = $properti->tipeKamar->first()->fasilitas_tipe ?? '';
                            $fasilitasArray = explode(',', $fasilitasStr);
                        } elseif ($tipe === 'kontrakan') {
                            $fasilitasArray = explode(',', $properti->fasilitas ?? '');
                        }
                        
                        if (empty(array_filter($fasilitasArray))) {
                            $fasilitasArray = ['Lemari Pakaian', 'Meja Belajar', 'Kursi', 'Kamar Mandi', 'Listrik Termasuk', 'Parkir Motor'];
                        }
                    ?>
                    <div class="flex flex-col gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fasilitasArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(trim($item) !== ''): ?>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-[#6b7280] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[#090a0b]"><?php echo e(trim($item)); ?></span>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipe === 'kosan' && $properti->tipeKamar->isNotEmpty()): ?>
                <!-- SECTION TIPE KAMAR -->
                <div class="py-8 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-[#090a0b] mb-5">Pilih Tipe Kamar</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $properti->tipeKamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div wire:click="$set('selectedTipeKamarId', <?php echo e($tk->id); ?>)" 
                                 class="cursor-pointer border-2 rounded-2xl p-5 transition-all duration-200 relative overflow-hidden group <?php echo e($selectedTipeKamarId === $tk->id ? 'border-[#1967d2] bg-blue-50/50 shadow-sm' : 'border-[#e5e7eb] hover:border-[#32baff] hover:shadow-sm'); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedTipeKamarId === $tk->id): ?>
                                <div class="absolute top-0 right-0 bg-[#1967d2] text-white p-1 rounded-bl-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-bold text-[#090a0b] text-lg"><?php echo e($tk->nama_tipe); ?></h4>
                                </div>
                                <div class="mb-3">
                                    <span class="text-[#1967d2] font-extrabold text-xl">Rp <?php echo e(number_format($tk->harga_per_bulan, 0, ',', '.')); ?></span>
                                    <span class="text-xs text-[#6b7280] font-medium">/bulan</span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tk->fasilitas_tipe): ?>
                                <p class="text-sm text-[#6b7280] line-clamp-2 leading-relaxed">
                                    <?php echo e($tk->fasilitas_tipe); ?>

                                </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- SECTION PILIH KAMAR (ALA KURSI BIOSKOP) -->
                    <div class="bg-slate-50 p-6 md:p-8 rounded-3xl border border-slate-100 relative overflow-hidden">
                        <!-- Decorative background -->
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-100 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
                        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-sky-100 rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>
                        
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold text-[#090a0b] mb-5">Pilih Nomor Kamar Anda</h3>
                            
                            <div class="flex flex-wrap items-center gap-5 mb-8 text-sm font-medium">
                                <div class="flex items-center gap-2"><div class="w-5 h-5 bg-white border-2 border-[#e5e7eb] rounded-md shadow-sm"></div><span class="text-[#6b7280]">Tersedia</span></div>
                                <div class="flex items-center gap-2"><div class="w-5 h-5 bg-[#1967d2] rounded-md shadow-md ring-2 ring-blue-200"></div><span class="text-[#090a0b]">Pilihan Anda</span></div>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 bg-gray-100 rounded-md border border-gray-200 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <span class="text-gray-400 line-through">Terisi</span>
                                </div>
                            </div>

                            <?php
                                $activeTipe = $properti->tipeKamar->firstWhere('id', $selectedTipeKamarId);
                            ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTipe && $activeTipe->kamar->isNotEmpty()): ?>
                                <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 gap-3 sm:gap-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activeTipe->kamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kamar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kamar->status_kamar === 'tersedia'): ?>
                                            <button type="button" wire:click="selectKamar(<?php echo e($kamar->id); ?>, '<?php echo e($kamar->status_kamar); ?>')"
                                                class="aspect-square flex flex-col items-center justify-center rounded-xl font-bold text-sm sm:text-base transition-all duration-300 <?php echo e($selectedKamarId === $kamar->id ? 'bg-[#1967d2] text-white shadow-lg shadow-blue-500/30 scale-[1.05] ring-2 ring-white' : 'bg-white text-[#090a0b] border-2 border-[#e5e7eb] hover:border-[#32baff] hover:text-[#1967d2] hover:shadow-md hover:-translate-y-1'); ?>">
                                                <?php echo e($kamar->nomor_kamar); ?>

                                            </button>
                                        <?php else: ?>
                                            <div class="aspect-square flex flex-col items-center justify-center rounded-xl bg-gray-100 border border-gray-200 text-gray-400 cursor-not-allowed shadow-inner">
                                                <svg class="w-4 h-4 mb-1 text-gray-400 opacity-70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                                <span class="text-xs sm:text-sm font-semibold opacity-70"><?php echo e($kamar->nomor_kamar); ?></span>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                    <p class="text-[#6b7280] font-medium">Belum ada data kamar untuk tipe ini.</p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Aturan -->
                <div class="py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-[#090a0b] mb-4">Aturan Kos / Kontrakan</h3>
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <?php
                            $aturanText = $tipe === 'kosan' ? ($properti->peraturan_kos ?? 'Tidak ada aturan khusus yang dicantumkan. Harap menjaga ketertiban dan kebersihan bersama.') : ($properti->peraturan_kontrakan ?? 'Tidak ada aturan khusus yang dicantumkan. Harap menjaga properti dengan baik.');
                            $aturanArray = array_filter(array_map('trim', explode("\n", $aturanText)));
                        ?>
                        <ul class="flex flex-col gap-3 text-[#6b7280] leading-relaxed">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $aturanArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aturan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span><?php echo e($aturan); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Ulasan -->
                <div class="py-6">
                    <h3 class="text-xl font-bold text-[#090a0b] mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 22 20">
                            <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                        </svg>
                        <?php echo e(number_format($properti->ulasan->avg('rating') ?? 0, 1)); ?> · <?php echo e($properti->ulasan->count()); ?> ulasan
                    </h3>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($properti->ulasan->count() > 0): ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $properti->ulasan->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ulasan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden border border-gray-100">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($ulasan->user->name ?? 'User')); ?>&background=random" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#090a0b]"><?php echo e($ulasan->user->name ?? 'Pengguna'); ?></h4>
                                            <p class="text-sm text-[#6b7280]"><?php echo e($ulasan->created_at->translatedFormat('F Y')); ?></p>
                                        </div>
                                    </div>
                                    <p class="text-[#6b7280] text-base leading-relaxed"><?php echo e(Str::limit($ulasan->komentar, 150)); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-50 p-6 rounded-2xl text-center border border-slate-100">
                            <p class="text-[#6b7280] font-medium">Belum ada ulasan untuk properti ini. Jadilah yang pertama memberikan ulasan setelah Anda menginap!</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </div>
            
            <!-- Kolom Kanan (35% Sticky Booking Card) -->
            <div class="lg:w-[35%] relative mt-8 lg:mt-0 pb-20 lg:pb-0">
                <div class="sticky top-24 bg-white border border-[#e5e7eb] rounded-3xl shadow-2xl shadow-blue-900/5 p-6 md:p-8 z-20">
                    
                    <!-- Harga & Label -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipe === 'kosan'): ?>
                                <span class="text-4xl font-extrabold text-[#1967d2] block mb-1 tracking-tight">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedTipeKamarId && $properti->tipeKamar->where('id', $selectedTipeKamarId)->isNotEmpty()): ?>
                                        Rp <?php echo e(number_format($properti->tipeKamar->where('id', $selectedTipeKamarId)->first()->harga_per_bulan ?? 0, 0, ',', '.')); ?>

                                    <?php else: ?>
                                        <?php echo e($properti->harga_range ?? 'Hubungi Pemilik'); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                                <span class="text-[#6b7280] font-medium text-base">/ bulan</span>
                            <?php else: ?>
                                <span class="text-4xl font-extrabold text-[#1967d2] block mb-1 tracking-tight">Rp <?php echo e(number_format($properti->harga_sewa_tahun, 0, ',', '.')); ?></span>
                                <span class="text-[#6b7280] font-medium text-base">/ tahun</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-6 border-t border-b border-gray-100 py-5 bg-slate-50/50 rounded-xl px-4 mt-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipe === 'kosan'): ?>
                            <?php
                                $aktifTipe = $properti->tipeKamar->firstWhere('id', $selectedTipeKamarId);
                                $kamarDipilih = $aktifTipe ? $aktifTipe->kamar->firstWhere('id', $selectedKamarId) : null;
                            ?>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[#6b7280] text-sm font-medium">Tipe Kamar</span>
                                <span class="font-bold text-[#090a0b] bg-white px-2 py-1 rounded shadow-sm border border-gray-100"><?php echo e($aktifTipe->nama_tipe ?? '-'); ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[#6b7280] text-sm font-medium">Nomor Kamar</span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kamarDipilih): ?>
                                    <span class="font-extrabold text-white bg-[#1967d2] px-3 py-1 rounded-md text-sm shadow-sm ring-2 ring-blue-100">Kamar <?php echo e($kamarDipilih->nomor_kamar); ?></span>
                                <?php else: ?>
                                    <span class="text-amber-600 text-xs font-bold bg-amber-50 px-2 py-1 rounded-md border border-amber-100">Pilih kamar di samping</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="flex justify-between items-center">
                                <span class="text-[#6b7280] text-sm font-medium">Ketersediaan</span>
                                <span class="font-extrabold text-[#1967d2] bg-blue-50 px-3 py-1.5 rounded-md text-sm">Sisa <?php echo e($properti->sisa_kamar); ?> Unit</span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <form wire:submit.prevent="buatBooking">
                        
                        <div class="grid grid-cols-1 border border-[#e5e7eb] rounded-2xl overflow-hidden mb-5 bg-white shadow-sm divide-y divide-[#e5e7eb] focus-within:ring-2 focus-within:ring-[#1967d2] focus-within:border-[#1967d2] transition-all">
                            <div class="relative group">
                                <div class="px-4 py-3 hover:bg-slate-50 transition-colors">
                                    <label class="block text-[10px] font-extrabold text-[#6b7280] group-focus-within:text-[#1967d2] uppercase mb-1 tracking-wider transition-colors cursor-pointer">Tanggal Masuk</label>
                                    <input type="date" wire:model="tanggalCheckIn" class="w-full border-none p-0 focus:ring-0 text-base font-bold text-[#090a0b] bg-transparent outline-none cursor-pointer">
                                </div>
                            </div>
                            <div class="relative group">
                                <div class="px-4 py-3 hover:bg-slate-50 transition-colors">
                                    <label class="block text-[10px] font-extrabold text-[#6b7280] group-focus-within:text-[#1967d2] uppercase mb-1 tracking-wider transition-colors cursor-pointer">Durasi Sewa</label>
                                    <select wire:model.live="durasiSewa" class="w-full border-none p-0 focus:ring-0 text-base font-bold text-[#090a0b] bg-transparent outline-none cursor-pointer">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tipe === 'kosan'): ?>
                                            <option value="1">1 Bulan</option>
                                            <option value="3">3 Bulan</option>
                                            <option value="6">6 Bulan</option>
                                            <option value="12">1 Tahun</option>
                                        <?php else: ?>
                                            <option value="1">1 Tahun</option>
                                            <option value="2">2 Tahun</option>
                                            <option value="3">3 Tahun</option>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tanggalCheckIn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mb-4 block font-medium bg-red-50 px-2 py-1 rounded"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
                            <div class="mb-5 text-sm text-red-600 bg-red-50 p-4 rounded-xl border border-red-100 font-semibold shadow-sm flex items-start gap-2">
                                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php echo e(session('error')); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <button type="submit" 
                                    <?php if($tipe === 'kosan' && !$selectedKamarId): ?> disabled <?php endif; ?>
                                    class="w-full rounded-2xl px-4 py-4 text-lg font-bold text-white shadow-lg transition-all active:scale-[0.98]
                                    <?php echo e(($tipe === 'kosan' && !$selectedKamarId) ? 'bg-gray-300 text-gray-500 cursor-not-allowed shadow-none' : 'bg-[#1967d2] hover:bg-[#0f4fb5] shadow-blue-500/30 hover:shadow-xl'); ?>">
                                Ajukan Sewa
                            </button>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="flex justify-center items-center gap-2 w-full text-center rounded-2xl bg-[#1967d2] px-4 py-4 text-lg font-bold text-white shadow-lg shadow-blue-500/30 transition-all hover:bg-[#0f4fb5] hover:shadow-xl active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                Masuk untuk Menyewa
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="mt-5 text-center">
                            <p class="text-xs text-[#6b7280] font-medium flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Anda belum dikenakan biaya apa pun saat ini.
                            </p>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\appkonkos_2\resources\views/livewire/front/detail-properti.blade.php ENDPATH**/ ?>