/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/wire-elements/modal/src/ModalComponent.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require("daisyui")
    ],
}

