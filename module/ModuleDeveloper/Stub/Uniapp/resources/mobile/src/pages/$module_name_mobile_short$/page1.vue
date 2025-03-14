<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="page1" :has-back="false"/>
        <view>
            page1
        </view>
        <app-tab-bar/>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../brick/components/Common/mixins/store";

export default {
    name: "page1",
    mixins: [StoreBaseUiMixin],
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {
            console.log('page1 init')
        },
    }
}
</script>
