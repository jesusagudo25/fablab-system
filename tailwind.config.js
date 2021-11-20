module.exports = {
  purge: ['./assets/js/*/*.js',
          './*.php',
          './*/*.php',
          './*/*/*.php',
            './*/*/*/*.php'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
