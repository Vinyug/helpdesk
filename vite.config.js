import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import path from 'path'
import WindiCSS from 'vite-plugin-windicss'


export default defineConfig({
  plugins: [
    laravel([
      'resources/js/app.js',
      'resources/css/app.css',
    ]),
    WindiCSS({
      scan: {
        include: ['resources/**/*.{vue,html,js,ts,jsx,tsx,css}'],
      },
    }),
  ],
  resolve: {
    alias: {
      '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
    }
  },
})