<template>
    <view>
        <view class="pb-cover" :class="classList" :style="styleList" :id="uid">
            <!-- #ifdef MP-WEIXIN -->
            <image class="image" lazy-load="true" fade-show
                   :mode="contain?'aspectFit':'aspectFill'"
                   :src="src"/>
            <!-- #endif -->
            <!-- #ifdef H5 -->
            <img class="image" v-lazy="src"
                 :style="{objectFit:contain?'contain':'cover'}"/>
            <!-- #endif -->
            <!-- #ifdef APP-PLUS -->
            <image v-if="imageShow"
                   class="image" fade-show
                   :mode="contain?'aspectFit':'aspectFill'"
                   :src="src"/>
            <view v-else class="image-holder"></view>
            <!-- #endif -->
        </view>
    </view>
</template>

<script>

let ID_NUMBER = 0;
export default {
    name: "c-cover",
    props: {
        src: {
            type: String,
            default: ''
        },
        ratio: {
            type: String,
            default: '1-1'
        },
        contain: {
            type: Boolean,
            default: false
        },
        round: {
            type: Boolean,
            default: false
        },
        borderRadius: {
            type: String,
            default: '0'
        },
        backgroundColor: {
            type: String,
            default: '#F8F8F8'
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
        this.uid = 'CCover' + (ID_NUMBER++)
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
            if (this.contain) {
                classList.push('contain')
            }
            return classList
        },
        styleList() {
            let styleList = {}
            if (this.borderRadius) {
                styleList.borderRadius = this.borderRadius
            }
            if (this.round) {
                styleList.borderRadius = '50%'
            }
            if (this.backgroundColor) {
                styleList.backgroundColor = this.backgroundColor
            }
            return styleList
        }
    },
}
</script>

<style lang="less" scoped>
.pb-cover {
    position: relative;
    overflow: hidden;

    &:after {
        content: '';
        display: block;
        padding-bottom: 100%;
    }

    &.contain {
        .image {
            object-fit: contain;
        }
    }

    &.ratio-1-1 {
        &:after {
            padding-bottom: 100%;
        }
    }

    &.ratio-2-1 {
        &:after {
            padding-bottom: 50%;
        }
    }

    &.ratio-3-1 {
        &:after {
            padding-bottom: 33.3333333333%;
        }
    }

    &.ratio-3-2 {
        &:after {
            padding-bottom: 66.6666666667%;
        }
    }

    &.ratio-2-3 {
        &:after {
            padding-bottom: 150%;
        }
    }

    &.ratio-3-4 {
        &:after {
            padding-bottom: 133.3333333333%;
        }
    }

    .image, .image-holder {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .image-holder {
        background-color: #FFFFFF;
    }
}
</style>
