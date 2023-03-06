import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import path from 'path'

export default defineConfig({
  plugins: [
    laravel([
      'resources/js/app.js',
      // 'resources/js/homepage.js' Exemple js/css page d'accueil à mettre dans la vue : @vite(['resources/js/homepage.js']
    ]),
  ],
  resolve: {
    alias: {
      '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
    }
  },
})