<x-guest-layout>
    <x-autentikasi.kartu-autentikasi>
        <x-slot name="logo">
            <x-autentikasi.logo-kartu />
        </x-slot>

        <x-formulir.error-validasi class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-formulir.label for="name" value="{{ __('Name') }}" />
                <x-formulir.input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-formulir.label for="email" value="{{ __('Email') }}" />
                <x-formulir.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-formulir.label for="no_telepon" value="{{ __('No. Telepon') }}" />
                <x-formulir.input id="no_telepon" class="block mt-1 w-full" type="text" name="no_telepon" :value="old('no_telepon')" required autocomplete="tel" />
            </div>

            <div class="mt-4">
                <x-formulir.label for="password" value="{{ __('Password') }}" />
                <x-formulir.input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-formulir.label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-formulir.input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-formulir.label for="terms">
                        <div class="flex items-center">
                            <x-formulir.kotak-centang name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-formulir.label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-formulir.tombol class="ms-4">
                    {{ __('Register') }}
                </x-formulir.tombol>
            </div>
        </form>
    </x-autentikasi.kartu-autentikasi>
</x-guest-layout>
