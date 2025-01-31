import {defineConfig} from 'vite'
import zermattStyles from 'zermatt-core/server/scss.mjs'
import zermattWatch from 'zermatt-core/server/watch.mjs'

const args = process.argv.slice(2)
const withReload = !args.includes('--noreload')

export default defineConfig({
    server: {
        cors: true,
        host: 'localhost',
        port: 5173
    },
    plugins: [
        {
            name: 'zermatt-watch',
            async configureServer(server) {
                await zermattWatch(server, withReload)
            }
        },
        {
            name: 'zermatt-styles',
            async buildStart() {
                await zermattStyles()
                //await zermattStyles([path.join(path.dirname(fileURLToPath(import.meta.url)), '/scss/custom-entry-file.scss')]) // Custom SASS
                //await zermattStyles([]) // Bypass all
            }
        }
    ],
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern'
            }
        }
    },
    build: {
        manifest: true,
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name && assetInfo.name.endsWith('.css')) {
                        return 'assets/zermatt.css'
                    }
                    return 'assets/[name].[hash][extname]'
                }
            }
        }
    }
})
