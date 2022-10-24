const defaultTheme = require('tailwindcss/defaultTheme.js');

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'dark-blue': '#1A154E',
                'dark-red': '#B00D02',
                'dark-green': '#02B033',

                'primary-50': '#f5f3ff',
                'primary-100': '#ede9fe',
                'primary-200': '#ddd6fe',
                'primary-300': '#c4b5fd',
                'primary-400': '#a78bfa',
                'primary-500': '#8b5cf6',
                'primary-600': '#7c3aed',
                'primary-700': '#6d28d9',
                'primary-800': '#5b21b6',
                'primary-900': '#4c1d95',
            }
        },
    },
    plugins: [require('@tailwindcss/forms')],
}
