<template>
    <view>
        <xinyu-canvas-drawer ref="poster" :width="width" :height="height"></xinyu-canvas-drawer>
    </view>
</template>

<script>
import XinyuCanvasDrawer from "./xinyu-canvas-drawer";

export default {
    name: "bui-image-design",
    components: {XinyuCanvasDrawer},
    data() {
        return {
            width: 100,
            height: 100,
        }
    },
    methods: {
        async render(imageConfig) {
            // console.log('imageConfig', imageConfig)
            const posterRef = this.$refs.poster;
            await posterRef.init()
            this.width = imageConfig.width
            this.height = imageConfig.height
            posterRef.clear()
            await posterRef.setSize(imageConfig.width, imageConfig.height)
            return new Promise((resolve, reject) => {
                this.$nextTick(() => {
                    if (imageConfig.backgroundColor) {
                        posterRef.setBackgroundColor(imageConfig.backgroundColor)
                    }
                    for (const block of imageConfig.blocks) {
                        switch (block.type) {
                            case 'text':
                            case 'rect':
                            case 'image':
                            case 'qrcode':
                            case 'maskColor':
                                posterRef.addBlock(block.type, block)
                                break
                            default:
                                console.error('未知的block类型', block.type, block)
                                break
                        }
                    }
                    posterRef.draw().then(res => {
                        resolve(res)
                    }).catch(err => {
                        reject(err)
                    })
                })
            })
        }
    }
}
</script>

<style scoped>

</style>
