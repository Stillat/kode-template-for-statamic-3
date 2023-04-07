module.exports = {
  content: [
    './resources/**/*.antlers.html',
    './resources/**/*.blade.php',
    './resources/**/*.vue',
    './content/**/*.md'
  ],
  theme: {
    extend: {
        fontFamily: {
            sans: ['Roboto', 'sans-serif']
        },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
