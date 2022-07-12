const defaultTheme = require('tailwindcss/defaultTheme');

function withOpacity(variableName) {
    return ({ opacityValue }) =>
       opacityValue ?
           `rgb(var(${variableName}) / ${opacityValue})` :
           `rgb(var(${variableName}))`;
}

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue'
    ],

    theme: {
        extend: {
            colors: {
                bgMain: withOpacity('--color-bg-main'),
                bgCard: withOpacity('--color-bg-card'),
            },
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
