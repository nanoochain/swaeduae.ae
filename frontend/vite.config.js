import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  server: {
    port: 3000,
    host: true,  // listens on all network interfaces (0.0.0.0)
    proxy: {
      '/api': 'http://localhost:8000'  // proxy API requests to Laravel backend
    }
  },
  base: '/',
})
