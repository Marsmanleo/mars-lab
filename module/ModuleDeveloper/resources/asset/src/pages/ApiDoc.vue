<template>
    <div>
        <rapi-doc
            ref="api"
            render-style="read"
            allow-spec-url-load="false"
            allow-spec-file-load="false"
            allow-authentication="false"
            allow-server-selection="false"
            primary-color="#656DE6"
            :heading-text="headingText"
            :style="{height:height,width:'100%'}"
        ></rapi-doc>
        <div class="pb-api-doc-new-window">
            <a :href="windowHref" target="_blank">
                <i class="iconfont icon-desktop"></i>
                新窗口打开
            </a>
        </div>
    </div>
</template>

<script>
const rapidoc = require('rapidoc');
export default {
    name: 'ApiDoc',
    components: {
        rapiDoc: rapidoc.default
    },
    computed: {
        headingText() {
            return window.document.title;
        },
        windowHref() {
            return window.location.href;
        },
    },
    data() {
        return {
            height: '100vh',
        }
    },
    beforeMount() {
        if (window.parent === window) {
            $('.ub-panel-dialog').addClass('no-foot')
            $('.panel-dialog-foot').remove();
            this.height = '100vh';
        } else {
            this.height = 'calc(100vh - 50px)'
        }
    },
    mounted() {
        this.$refs.api.loadSpec(window.__moduleDeveloperApiDocSpec)
    },
    methods: {
        //
    }
}
</script>

<style lang="less">
.pb-api-doc-new-window {
    display: block;
    position: fixed;
    top: 0;
    right: 0;
    line-height: 48px;
    padding: 0 0.5rem;

    a {
        color: #FFF;
        display: inline-block;
        line-height: 48px;
    }
}
</style>
