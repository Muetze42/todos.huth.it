const defaultTheme = require('tailwindcss/defaultTheme.js')

console.log(defaultTheme.screens.sm)

/** @type {import("tailwindcss").Config} */
module.exports = {
  content: ['./resources/views/app/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
  theme: {
    container: {
      center: true,
      padding: {
        DEFAULT: '2rem'
      },
      screens: {
        sm: defaultTheme.screens.sm,
        md: defaultTheme.screens.md,
        lg: defaultTheme.screens.lg,
        xl: defaultTheme.screens.xl
      }
    },
    extend: {
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        mono: ['Fira Code VF', ...defaultTheme.fontFamily.mono]
      }
    }
  },
  plugins: [require('@tailwindcss/forms'), require('tailwind-scrollbar')({ nocompatible: true })]
}
