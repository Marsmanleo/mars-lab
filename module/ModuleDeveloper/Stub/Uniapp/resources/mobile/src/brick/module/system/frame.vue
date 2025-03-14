<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="pb-system-frame">
        <view class="phone">
            <iframe v-if="load" :src="url" frameborder="0"></iframe>
            <!-- #ifdef H5 -->
            <view class="qrcode">
                <div class="qrcode-container">
                    <canvas id="qrcodePage" canvas-id="qrcodePage" style="width:200px;height:200px;"></canvas>
                </div>
                <div>扫一扫手机访问</div>
            </view>
            <!-- #endif -->
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";
import {AgentUtil} from "../../lib/ui";
// #ifdef H5
import QRCode from "../../lib/weapp-qrcode";
// #endif

export default {
    name: "frame",
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            url: null,
            load: false,
        }
    },
    onReady() {
        this.$starter.boot()
    },
    onShow() {
        const url = this.$r.getQuery('url')
        if (AgentUtil.isMobile()) {
            this.$r.relaunch()
        } else {
            const l = window.location
            if (this.$r.mode() === this.$r.MODE_HISTORY) {
                this.url = `${l.protocol}//${l.host}${url}`
            } else {
                this.url = `${l.protocol}//${l.host}${l.pathname}#${url}`
            }
            setTimeout(() => {
                this.load = true
            }, 0)
        }
    },
    mounted() {
        // #ifdef H5
        this.createQrcode()
        // #endif
    },
    methods: {
        // #ifdef H5
        createQrcode() {
            if (!window && window.innerWidth && (window.innerWidth > 800)) {
                return;
            }
            console.log('createQrcode', this.url)
            const qrcode = new QRCode('qrcodePage', {
                text: this.url,
                width: 200,
                height: 200,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.L,
                callback: (res) => {
                    console.log('createQrcode.result', res)
                }
            });
        }
        // #endif
    }
}
</script>

<style lang="less" scoped>
// #ifdef H5
.pb-system-frame {
    position: fixed;
    top: 0px;
    bottom: 0px;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    flex-direction: column;

    .phone {
        padding: 30px 5px 50px 5px;
        border-radius: 40px;
        background: #C7CBD2;
        height: 100%;
        max-height: 700px;
        width: 375px;
        margin: 0 auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        position: relative;

        &:after {
            content: '';
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            position: absolute;
            bottom: 5px;
            left: 50%;
            margin-left: -20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .qrcode {
            position: absolute;
            width: 220px;
            height: 250px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            right: -300px;
            bottom: 20px;
            background: #FFF;
            border-radius: 5px;
            text-align: center;
            color: var(--color-tertiary);

            .qrcode-container {
                padding: 10px;
            }
        }

        iframe {
            width: 100%;
            height: 100%;
            border-radius: 5px;
        }
    }
}

// #endif
</style>


