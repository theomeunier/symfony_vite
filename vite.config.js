import { defineConfig } from 'vite'
import reactRefresh from '@vitejs/plugin-react-refresh'
import {resolve} from 'path'

//on crée notre plugion pour refresh twig
const twigRefreshPlugin = {
  name: 'twig-refresh',
  configureServer ({watcher, ws}){
    watcher.add(resolve('templates/**/*.twig'))
    watcher.on('change', function (path){
      if (path.endsWith('.twig')){
        ws.send({
          type: 'full-reload'
        })
      }
    })
  }
}

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [reactRefresh(), twigRefreshPlugin],
  root: './assets',
  base: '/assets/',
  server: {
    watch: {
      disableGlobbing: false,
    }
  },
  build: {
    //on crée un fichier 'manifest.json' information sur notre build
    manifest: true,
    assetsDir: '',
    outDir: '../public/assets/',
    rollupOptions: {
      //si on veux qu'un seul points d'entres
      output: {
        //manualChunks: undefined
      },
      input: {
        'main.jsx': './assets/main.jsx'
      }
    }
  }
})
