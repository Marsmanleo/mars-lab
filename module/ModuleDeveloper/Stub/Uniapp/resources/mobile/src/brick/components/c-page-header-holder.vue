<template>
    <view :style="{height:headerStatusHeightRaw,backgroundColor:backgroundColor}"></view>
</template>

<script>
import {PageHeaderMixin} from "./Common/mixins/header";

export default {
    name: "c-page-header-holder",
    mixins: [PageHeaderMixin],
    data() {
        return {
            cName: 'c-page-header-holder',
        }
    },
}
</script>

