@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-[#1967d2] focus:ring-[#1967d2] rounded-full px-5 py-2.5 shadow-sm text-sm']) !!}>
