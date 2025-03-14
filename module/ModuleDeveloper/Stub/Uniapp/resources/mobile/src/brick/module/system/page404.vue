<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "page404",
    mixins: [StoreBaseUiMixin],
    onReady() {
        this.$starter.boot()
    },
}
</script>

