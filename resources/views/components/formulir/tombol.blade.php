<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2.5 bg-[#1967d2] border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#0f4fb5] focus:bg-[#0f4fb5] active:bg-[#0d3f6d] focus:outline-none focus:ring-2 focus:ring-[#1967d2] focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150 shadow-md']) }}>
    {{ $slot }}
</button>
