module.exports = {
  purge: ['./assets/js/*/*.js',
        './assets/css/*.css',
          './*.php',
          './*/*.php',
          './*/*/*.php',
            './*/*/*/*.php'],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        'new-bg': 'rgba(0,0,0,.4)',
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
