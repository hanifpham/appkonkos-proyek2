{{-- Sekitar Kampus & Perusahaan --}}
<section class="bg-white py-16" id="sekitar-kampus">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-[#090a0b] sm:text-3xl">Hunian Dekat Kampus & Perkantoran</h2>
            <p class="mt-2 text-sm text-[#6b7280]">Tinggal lebih dekat dengan tempat aktivitas utama kamu</p>
        </div>

        @php
            $locations = [
                ['nama' => 'Politeknik Negeri Indramayu', 'properti' => '85+ Properti', 'ikon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'ikon2' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 a0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222'],
                ['nama' => 'Universitas Indonesia', 'properti' => '120+ Properti', 'ikon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'ikon2' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222'],
                ['nama' => 'UGM Yogyakarta', 'properti' => '95+ Properti', 'ikon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'ikon2' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222'],
                ['nama' => 'ITB Bandung', 'properti' => '80+ Properti', 'ikon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'ikon2' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222'],
                ['nama' => 'Universitas Brawijaya', 'properti' => '110+ Properti', 'ikon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'ikon2' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222'],
                ['nama' => 'Sudirman CBD', 'properti' => '200+ Properti', 'ikon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'ikon2' => ''],
                ['nama' => 'TB Simatupang', 'properti' => '75+ Properti', 'ikon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'ikon2' => ''],
                ['nama' => 'UNDIP Semarang', 'properti' => '65+ Properti', 'ikon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'ikon2' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222'],
                ];
            $kampus = [
                ['nama' => 'Universitas Gadjah Mada', 'abbr' => 'UGM', 'kota' => 'Jogja', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/9/9f/Emblem_of_Universitas_Gadjah_Mada.svg/langid-330px-Emblem_of_Universitas_Gadjah_Mada.svg.png'],
                ['nama' => 'Universitas Diponegoro', 'abbr' => 'UNDIP', 'kota' => 'Semarang', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/9/9b/UNDIPOfficial.png/330px-UNDIPOfficial.png'],
                ['nama' => 'Universitas Indonesia', 'abbr' => 'UI', 'kota' => 'Depok', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/0/0f/Makara_of_Universitas_Indonesia.svg/langid-330px-Makara_of_Universitas_Indonesia.svg.png'],
                ['nama' => 'Universitas Padjadjaran', 'abbr' => 'UNPAD', 'kota' => 'Jatinangor', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/8/80/Lambang_Universitas_Padjadjaran.svg/langid-330px-Lambang_Universitas_Padjadjaran.svg.png'],
                ['nama' => 'Politeknik Keuangan Negara STAN', 'abbr' => 'STAN', 'kota' => 'Jakarta', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/5/54/STAN.jpg'],
                ['nama' => 'Universitas Brawijaya', 'abbr' => 'UB', 'kota' => 'Malang', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bb/Logo_Universitas_Brawijaya.svg/langid-330px-Logo_Universitas_Brawijaya.svg.png'],
                ['nama' => 'Universitas Airlangga', 'abbr' => 'UNAIR', 'kota' => 'Surabaya', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/65/Logo-Branding-UNAIR-biru.png/330px-Logo-Branding-UNAIR-biru.png'],
                ['nama' => 'Politeknik Negeri Indramayu', 'abbr' => 'POLINDRA', 'kota' => 'Indramayu', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Politeknik-Negeri-Indramayu.png/330px-Politeknik-Negeri-Indramayu.png'],
                ['nama' => 'Institut Teknologi Bandung', 'abbr' => 'ITB', 'kota' => 'Bandung', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/thumb/9/95/Logo_Institut_Teknologi_Bandung.png/330px-Logo_Institut_Teknologi_Bandung.png'],
                ['nama' => 'Pabrik Unilever', 'abbr' => 'Unilever', 'kota' => 'Cikarang', 'logo' => 'https://upload.wikimedia.org/wikipedia/id/3/37/Unilever.png'],
                ['nama' => 'Pabrik Toyota', 'abbr' => 'Toyota', 'kota' => 'Karawang', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Toyota_carlogo.svg/330px-Toyota_carlogo.svg.png'],
                
            ];
        @endphp

        <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($kampus as $k)
                <a href="{{ route('cari', ['lokasi' => $k['nama']]) }}" class="group flex items-center gap-4 rounded-xl border border-[#e5e7eb] bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-[#1967d2] hover:shadow-md" id="kampus-{{ Str::slug($k['abbr']) }}">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-full border border-slate-100 bg-white">
                        <img src="{{ $k['logo'] }}" alt="Logo {{ $k['abbr'] }}" class="h-full w-full object-cover">
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="truncate text-sm font-bold text-[#090a0b] transition group-hover:text-[#1967d2]">{{ $k['abbr'] }}</h3>
                        <p class="truncate text-xs text-[#6b7280]">{{ $k['kota'] }}</p>
                    </div>
                </a>
            @endforeach
            
            {{-- Card Lihat Semua --}}
            <a href="{{ route('cari') }}" class="group flex items-center justify-center rounded-xl border border-[#e5e7eb] bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-[#1967d2] hover:shadow-md" id="kampus-lihat-semua">
                <span class="text-sm font-bold text-[#090a0b] transition group-hover:text-[#1967d2]">Lihat semua &rarr;</span>
            </a>
        </div>
    </div>
</section>
