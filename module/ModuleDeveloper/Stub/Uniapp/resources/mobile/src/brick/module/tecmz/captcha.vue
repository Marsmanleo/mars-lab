<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view id="body">
        <!-- #ifdef H5 | APP-PLUS -->
        <c-page-header title="人机验证"/>
        <!-- #endif -->
        <!--        <view id="aa" style="background:#EEE;height:50vh;touch-action:none;"></view>-->
        <view v-if="url">
            <web-view class="pb-captcha-iframe"
                      ref="webview"
                      :src="url"
                      @message="onMessage"
                      :fullscreen="false"></web-view>
        </view>
    </view>
</template>

<script>
import {RouterStartForCallbackMixin} from "../../components/Common/mixins/router";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "captcha",
    mixins: [RouterStartForCallbackMixin, StoreBaseUiMixin],
    data() {
        return {
            url: null,
            msg: null,
            processed: [],
        }
    },
    mounted() {
        this.initListener()
    },
    destroyed() {
        // #ifdef H5
        window.removeEventListener("message", this.onWindowMessage, false);
        // #endif
    },
    methods: {
        init() {
            this.url = 'https://api.tecmz.com/api_res/captcha/online/' + this.config.appId
            // #ifdef APP-PLUS
            //此对象相当于html5plus里的plus.webview.currentWebview()。在uni-app里vue页面直接使用plus.webview.currentWebview()无效
            var currentWebview = this.$scope.$getAppWebview()
            setTimeout(function () {
                const wv = currentWebview.children()[0]
                wv.setStyle({top: 52})
            }, 100)
            // #endif
        },
        initListener() {
            if (!this.$refs.webview) {
                setTimeout(() => {
                    this.initListener()
                }, 100)
                return
            }
            // #ifdef H5
            window.addEventListener("message", this.onWindowMessage, false);
            // 禁止手机默认左右滑动行为
            // console.log('禁止手机默认左右滑动行为')
            // function touchHandlerDummy(e)
            // {
            //     console.log(e.type,e.target, e.cancelable)
            //     e.preventDefault();
            //     e.stopPropagation();
            // }
            // document.querySelector('#aa').addEventListener("touchstart", touchHandlerDummy, { passive: false,capture : true });
            // document.querySelector('#aa').addEventListener("touchmove", touchHandlerDummy, { passive: false ,capture : true});
            // document.querySelector('#aa').addEventListener("touchend", touchHandlerDummy, { passive: false,capture : true });
            // document.querySelector('#aa').addEventListener("touchcancel", touchHandlerDummy, { passive: false,capture : true });
            // history.pushState(null, null, document.URL);
            // window.addEventListener('popstate', function () {
            //     history.pushState(null, null, document.URL);
            // });
            // document.body.style.overflow = 'hidden'
            // document.body.style.touchAction = 'none'
            // document.body.addEventListener('MozTouchMove', function(e){e.preventDefault();});
            // document.body.addEventListener('touchstart', function (e) {
            //     console.log('touchstart', e)
            //     e.preventDefault()
            // }, false)
            // document.body.addEventListener('click', function (e) {
            //     console.log('touchstart', e)
            //     e.preventDefault()
            // }, false)
            // document.body.addEventListener('touchmove', function (e) {
            //     console.log('touchmove', e)
            //     e.preventDefault()
            //     return false
            // }, false)
            // document.body.addEventListener('touchcancel', function (e) {
            //     console.log('touchcancel', e)
            // }, false)
            // console.log('ok1');
            // #endif
        },
        onProcessMessage(msg) {
            console.log('onProcessMessage', JSON.stringify(msg))
            if (this.processed.indexOf(msg.id) >= 0) {
                console.log('onProcessMessage.duplicated', msg.id)
                return
            }
            this.processed.push(msg.id)
            // {"type":"verify","result":"succcess","key":"jPalVDp3eoUtTNFzKKqTATB8Rk23IAbi"}
            switch (msg.type) {
                case 'verify':
                    if (msg.result === 'success') {
                        console.log('success', msg.key)
                        let backIgnore = false
                        // #ifdef MP-WEIXIN
                        backIgnore = true
                        // #endif
                        this.callbackConfirm(msg, backIgnore)
                    }
                    break
            }
        },
        // onIframeMessage(e){
        //     console.log('onIframeMessage', e)
        //     // H5
        //     try {
        //         const msg = JSON.parse(e.data)
        //         if (msg.type) {
        //             this.onProcessMessage(msg)
        //         }
        //     } catch (e) {
        //     }
        // },
        onWindowMessage(e) {
            console.log('onWindowMessage', e)
            // H5
            try {
                let message = e.data.data.arg
                if (!message) {
                    message = e.data.data
                }
                const msg = JSON.parse(message)
                if (msg.type) {
                    this.onProcessMessage(msg)
                }
            } catch (e) {
            }
        },
        onMessage(e) {
            console.log('onMessage', e)
            // APP-PLUS | MP-WEIXIN
            const msgs = e.detail.data
            for (let m of msgs) {
                try {
                    const msg = JSON.parse(m)
                    if (msg.type) {
                        this.onProcessMessage(msg)
                    }
                } catch (e) {
                }
            }
        }
    }
}
</script>

<style lang="less">
// #ifdef H5
.pb-captcha-iframe {
    top: 50px !important;
    overflow: hidden !important;
    position: fixed !important;
    border: none;
    width: 100%;
    height: calc(100% - 50px);

    /deep/ iframe {
        width: 100% !important;
        height: 100% !important;
        border: none;
    }
}

// #endif
</style>
