module.exports = {
  content: ['./assets/js/tables/*/*.js',
          './assets/js/app/*.js',
          './*.php',
          './*/*.php',
          './*/*/*.php',
            './*/*/*/*.php'],
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
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
