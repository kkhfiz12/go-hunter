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
        brand: {
          DEFAULT: '#0284C7', // Ocean Azure
          dark: '#0369A1',    // Deep Sea
          light: '#E0F2FE',   // Sky Tint
          accent: '#F59E0B',  // Sunrise Amber
          secondary: '#10B981', // Emerald Compass
        },
        fairness: {
          fair: '#10B981',
          'fair-bg': '#ECFDF5',
          warning: '#F59E0B',
          'warning-bg': '#FFFBEB',
          getok: '#EF4444',
          'getok-bg': '#FEF2F2',
        }
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
        script: ['Caveat', 'cursive'],
      }
    },
  },
  plugins: [],
}