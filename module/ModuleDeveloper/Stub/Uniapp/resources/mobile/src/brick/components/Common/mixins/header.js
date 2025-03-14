export const PageHeaderMixin = {
    props: {
        title: {
            type: [String, null],
            default: ''
        },
        pageTitle: {
            type: [String, null],
            default: ''
        },
        backgroundColor: {
            type: String,
            default: 'var(--color-content-bg)'
        },
        color: {
            type: String,
            default: 'var(--color-text)'
        },
        hasTitle: {
            type: Boolean,
            default: true
        },
        hasBack: {
            type: Boolean,
            default: true
        },
        shadow: {
            type: Boolean,
            default: true
        },
        hasAction: {
            type: Boolean,
            default: false
        },
        customBackCallback: {
            type: Boolean,
            default: false
        },
        titleAlign: {
            type: String,
            default: 'center',
        }
    },
    computed: {
        headerTitleTextAlign() {
            if (this.SystemConfig.HeadMenuWidth > 0) {
                if (this.hasAction) {
                    return 'left'
                }
            }
            return this.titleAlign
        },
        headerStatusHeight() {
            return this.SystemConfig.StatusHeight + 'px'
        },
        headerStatusHeightRaw() {
            return this.SystemConfig.StatusHeightRaw + 'px'
        },
        headerContainerHeight() {
            return this.SystemConfig.HeadHeight + 'px'
        },
        headerHeight() {
            return (this.SystemConfig.HeadHeight + this.SystemConfig.StatusHeight) + 'px'
        },
        headerBodyPaddingRight() {
            return this.SystemConfig.HeadMenuWidth + 'px'
        },
        _headerTitlePaddingRightRaw() {
            let padding = 0
            if (this.hasBack && this.pageCanBack) {
                padding += 50
            }
            if (this.hasAction) {
                padding -= 50
            }
            padding = padding - this.SystemConfig.HeadMenuWidth
            return padding
        },
        headerTitlePaddingRight() {
            return Math.max(this._headerTitlePaddingRightRaw, 0) + 'px'
        },
        headerTitlePaddingLeft() {
            let padding = 0
            if (this.SystemConfig.HeadMenuWidth > 0) {
                if (this.hasAction) {
                    if (this.hasBack && this.pageCanBack) {
                        return padding + 'px'
                    }
                    return padding + 10 + 'px'
                }
            }
            if (this._headerTitlePaddingRightRaw < 0) {
                padding += -this._headerTitlePaddingRightRaw
            }
            return padding + 'px'
        },
    },
    data() {
        const pages = getCurrentPages()
        const pageCanBack = (pages.length > 1)
        return {
            pageCanBack
        }
    },
    watch: {
        title: {
            handler(n, o) {
                if (n && this.cName && ['c-page-header', 'c-page-header-holder', 'c-page-header-trans'].includes(this.cName)) {
                    uni.setNavigationBarTitle({
                        title: n
                    })
                    this.$store.commit('SET_PAGE_TITLE', n)
                }
            },
            immediate: true,
        },
        pageTitle: {
            handler(n, o) {
                if (n) {
                    uni.setNavigationBarTitle({
                        title: n
                    })
                }
            }
        }
    },
    methods: {
        doBack() {
            if (this.customBackCallback) {
                this.$emit('back')
            } else {
                this.$r.back()
            }
        }
    },
}
