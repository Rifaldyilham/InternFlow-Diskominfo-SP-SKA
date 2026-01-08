/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'primary': '#213448',
                'secondary': '#547792', 
                'accent': '#94B4C1',
                'background': '#EAE0CF',
            }
        },
    },
    plugins: [],
}