import { defineConfig } from 'vite'
import zermattLock from 'zermatt-core/scss.mjs'

export default defineConfig({
    plugins: [
        {
            name: 'zermatt-lock',
            async buildStart () {
                await zermattLock()
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
