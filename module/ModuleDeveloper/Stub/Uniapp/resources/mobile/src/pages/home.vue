<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header :title="config.siteName" :has-back="false"/>
        <view>
            home
        </view>
        <app-tab-bar/>
    </view>
</template>

<script>
import {StoreBaseConfigMixin, StoreBaseUiMixin, StoreUserMixin} from "../brick/components/Common/mixins/store";
import {ShareMixin} from "../brick/components/Common/mixins/share";

export default {
    mixins: [StoreUserMixin, StoreBaseConfigMixin, ShareMixin, StoreBaseUiMixin],
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    data() {
        return {}
    },
    methods: {
        init() {
            console.log('home init')
        },
    }

}
</script>
