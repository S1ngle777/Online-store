import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-yellow-100',
        'bg-blue-100',
        'bg-green-100',
        'bg-red-100',
        'text-yellow-800',
        'text-blue-800',
        'text-green-800',
        'text-red-800',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#C41E3A', // красный из молдавского флага
                    dark: '#A01830',
                },
                secondary: {
                    DEFAULT: '#FCD116', // желтый из молдавского флага
                    dark: '#E5BC14',
                },
                accent: {
                    DEFAULT: '#003DA5', // синий из молдавского флага
                    dark: '#003090',
                }
            }
        }
    },

    plugins: [forms],
};
