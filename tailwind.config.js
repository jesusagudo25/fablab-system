module.exports = {
  content: ['./assets/**/*.js',
          './index.php',
          './dashboard/**/*.php'],
  theme: {
    extend: {
      colors: {
        'new-bg': 'rgba(0,0,0,.4)',
      },
      width: {
        '1/7': '10%',
        '7/11': '54%',
        '2/7': '28%',
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
