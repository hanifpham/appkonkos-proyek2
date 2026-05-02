<div class="flex h-screen w-full overflow-hidden bg-white">
    <!-- Left Panel (Illustration) -->
    <div class="hidden w-1/2 flex-col items-center justify-center bg-[#1967d2] p-8 text-center text-white lg:flex">
        <h2 class="mb-4 text-4xl font-bold leading-tight">APPKONKOS</h2>
        <p class="mb-10 text-base leading-relaxed text-blue-100">Satu Aplikasi Untuk Semua Kebutuhan Kos dan Kontrakan Anda.</p>
        <img src="{{ asset('images/illustration-1.svg') }}" alt="Illustration" class="max-h-64 object-contain opacity-90" onerror="this.src='https://illustrations.popsy.co/blue/freelancer.svg'">
    </div>

    <!-- Right Panel (Form) -->
    <div class="flex w-full flex-col justify-center overflow-y-auto p-8 lg:w-1/2">
        <div class="mx-auto w-full max-w-md">
            <div class="mb-8 flex justify-center lg:justify-start">
                {{ $logo }}
            </div>
            
            {{ $slot }}
        </div>
    </div>
</div>
