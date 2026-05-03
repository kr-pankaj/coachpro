import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                quonix: {
                    purple: '#7C3AED',
                    magenta: '#C026D3',
                    pink: '#EC4899',
                    amber: '#F59E0B',
                    navy: '#050514',
                },
            },
        },
    },

    plugins: [forms],
};
