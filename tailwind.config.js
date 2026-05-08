import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './app/**/*.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#1967d2',
                    dark: '#0f4fb5',
                },
                secondary: '#32baff',
                apptext: '#090a0b',
                muted: '#6b7280',
                surface: '#ffffff',
                border: '#e5e7eb',
            },
            boxShadow: {
                'custom-hover': '0 20px 50px rgba(15, 79, 181, 0.12)',
            }
        },
    },

    plugins: [forms, typography],
};
