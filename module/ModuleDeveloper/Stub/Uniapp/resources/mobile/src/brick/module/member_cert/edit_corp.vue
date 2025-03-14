<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="企业实名认证"/>
        <view>
            暂不支持
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "edit_corp",
    mixins: [StoreBaseUiMixin],
}
</script>

