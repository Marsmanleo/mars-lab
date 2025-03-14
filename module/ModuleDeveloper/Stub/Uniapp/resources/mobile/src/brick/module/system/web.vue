<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <web-view class="web"
                  v-if="load"
                  :src="url"
                  frameborder="0"
                  @message="onMessage"></web-view>
    </view>
</template>

<script>
import {Router} from "../../lib/router";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: 'web',
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            url: null,
            load: false,
            messageProcessed: [],
        }
    },
    onReady() {
        this.$starter.boot()
    },
    onShow() {
        this.url = Router.getQuery('url')
        this.$nextTick(() => {
            this.load = true
            console.log('load', this.url)
        })
    },
    methods: {
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
        },
        onProcessMessage(msg) {
            console.log('onProcessMessage', JSON.stringify(msg))
            if (this.messageProcessed.indexOf(msg.id) >= 0) {
                console.log('onProcessMessage.duplicated', msg.id)
                return
            }
            this.messageProcessed.push(msg.id)
            switch (msg.type) {
            }
        },
    }
}
</script>
