<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="page2" :has-back="false"/>
        <view>
            page2
        </view>
        <app-tab-bar/>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../brick/components/Common/mixins/store";

export default {
    name: "page2",
    mixins: [StoreBaseUiMixin],
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {
            console.log('page2 init')
        },
    }
}
</script>
