/** @type {import('tailwindcss').Config} */
export default {
  // Définition des fichiers à scanner pour trouver les classes Tailwind
  // Indispensable pour purger le CSS en production et ne garder que l'utile
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      // Configuration de l'esthétique 'Google Antigravity' demandée
      // Couleurs claires, aérées et fluides, typiques du Material Design 3
      colors: {
        primary: '#1A73E8', // Bleu Google
        secondary: '#5F6368', // Gris foncé pour les textes secondaires
        background: '#F8F9FA', // Gris très clair pour les fonds
        surface: '#FFFFFF', // Blanc pur pour les cartes
        error: '#D93025', // Rouge Google pour les erreurs
      },
      fontFamily: {
        // Police typique Google (Roboto, ou à défaut sans-serif de base)
        sans: ['Roboto', 'Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
      },
      boxShadow: {
        // Ombres douces et diffuses pour l'effet "Antigravity" / flottaison
        'float': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'float-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
      }
    },
  },
  plugins: [],
}
