module.exports = {
  purge: ['./assets/js/tables/*.js',
          './assets/js/app/*.js',
          './assets/js/plugins/datepicker.bundle.js',
        './assets/css/tailwind.css',
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
      width: {
        '1/7': '10%',
        '2/7': '28%',
      }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
