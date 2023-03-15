const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            colors:{
                'custom-light-blue': '#2EA3F2',
                'custom-dark-blue': '#15314E',
                'custom-blue': '#2573F9',
                'custom-orange': '#FF8A3D',
                'custom-dark': '#333',
                'custom-grey': '#666',
            },
            fontFamily: {
                sans: ['Open Sans', 'Helvetica', 'Arial', 'Lucida', 'Sans-serif'],
                'share-tech': ['Share Tech', 'Helvetica', 'Arial', 'Lucida', 'Sans-serif']
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
