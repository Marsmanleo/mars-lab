<template>
    <view>
        <view class="pb-image" :class="classList" :style="styleList" :id="uid"
              @click="doClick">
            <!-- #ifdef MP-WEIXIN -->
            <image class="image" lazy-load="true" fade-show
                   mode="widthFix"
                   :src="src"/>
            <!-- #endif -->
            <!-- #ifdef H5 -->
            <img class="image" v-lazy="src"/>
            <!-- #endif -->
            <!-- #ifdef APP-PLUS -->
            <image v-if="imageShow"
                   class="image" fade-show
                   mode="widthFix"
                   :src="src"/>
            <view v-else class="image-holder"></view>
            <!-- #endif -->
        </view>
    </view>
</template>

<script>

let ID_NUMBER = 0;
export default {
    name: "c-image",
    props: {
        src: {
            type: String,
            default: ''
        },
        ratio: {
            type: String,
            default: '1-1'
        },
        borderRadius: {
            type: String,
            default: '0'
        },
        preview: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            timer: null,
            uid: '',
            imageShow: false,
        }
    },
    created() {
        this.uid = 'CImage' + (ID_NUMBER++)
    },
    mounted() {
        // #ifdef APP-PLUS
        const observer = uni.createIntersectionObserver(this);
        observer.relativeToViewport().observe('#' + this.uid, res => {
            if (this.imageShow) {
                return
            }
            if (res.boundingClientRect.top < this.SystemConfig.Height) {
                this.imageShow = true
                observer.disconnect()
            }
        })
        // #endif
    },
    computed: {
        classList() {
            let classList = ['ratio-' + this.ratio]
            return classList
        },
        styleList() {
            let styleList = {}
            if (this.borderRadius) {
                styleList.borderRadius = this.borderRadius
            }
            return styleList
        },
    },
    methods: {
        doClick() {
            if (this.preview) {
                uni.previewImage({
                    urls: [this.src]
                })
                this.$emit('on-preview', this.src)
            }
        },
    }
}
</script>

<style lang="less" scoped>
.pb-image {

    &.ratio-1-1 {
        .image-holder {
            &:after {
                padding-bottom: 100%;
            }
        }
    }

    &.ratio-2-1 {
        .image-holder {
            &:after {
                padding-bottom: 50%;
            }
        }
    }

    .image-holder {
        background-color: #F8F8F8;

        &:after {
            content: '';
            display: block;
            padding-bottom: 100%;
        }
    }

    .image {
        width: 100%;
    }
}
</style>
