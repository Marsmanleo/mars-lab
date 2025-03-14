<script>
    import {BrickUni} from "./brick/BrickUni";

    export default {
        onLaunch() {
            BrickUni.launch()
            // #ifdef H5
            // #endif
            // #ifdef APP-PLUS
            // #endif
            // #ifdef MP-WEIXIN
            // #endif

        },
    }
</script>

<style lang="less">
    @import "./config/style.less";
    // #ifdef H5
    @import "./brick/ui/style-h5";
    // #endif
    // #ifdef APP-PLUS
    @import "./brick/ui/style-app";
    // #endif
    // #ifdef MP-WEIXIN
    @import "./brick/ui/style-mp";
    // #endif
</style>

