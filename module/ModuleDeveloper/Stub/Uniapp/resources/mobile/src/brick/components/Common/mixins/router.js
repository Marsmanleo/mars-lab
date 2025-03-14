import {
    Router
} from "../../../lib/router";

export const RouterCallbackIdsSelectorMixin = {
    data() {
        return {
            selectIds: []
        }
    },
    watch: {
        value: {
            handler(n, o) {
                if (Array.isArray(n) && JSON.stringify(n) !== JSON.stringify(this.selectIds)) {
                    this.selectIds = n
                }
            },
            immediate: true
        }
    },
    methods: {
        hasId(id) {
            return this.selectIds && this.selectIds.includes(id)
        },
        doSelect(id) {
            if (this.selectIds.includes(id)) {
                this.selectIds.splice(this.selectIds.indexOf(id), 1)
            } else {
                if (this.config.max && this.config.max > 0 && this.config.max <= this.selectIds.length) {
                    this.$dialog.tipError(`最多选择${this.config.max}个`)
                    return
                }
                this.selectIds.push(id)
            }
        },
        doConfirm() {
            this.callbackConfirm(this.selectIds)
        },
        doCancel() {
            this.callbackCancel([])
        }
    }
}

export const RouterCallbackIdSelectorMixin = {
    data() {
        return {
            selectId: 0,
        }
    },
    watch: {
        value: {
            handler(n, o) {
                if (n !== this.selectId) {
                    this.selectId = n
                    this.prepareSelectedItem()
                }
            },
            immediate: true
        }
    },
    methods: {
        prepareSelectedItem() {
        },
        isId(id) {
            return this.selectId === id
        },
        doSelect(id) {
            this.selectId = id
        },
        doConfirm() {
            this.callbackConfirm(this.selectId)
        },
        doCancel() {
            this.callbackCancel(0)
        }
    }
}

export const RouterStartForCallbackMixin = {
    onReady() {
        this.$starter.boot(() => {
            this.initCall()
        })
    },
    data() {
        return {
            _e_: null,
            isInit: false,
            config: {},
            value: null
        }
    },
    mounted() {
        this._e_ = Router.getQuery('_e_')
        // console.log('RouterStartForCallbackMixin.mounted', this._e_)
        const initDataKey = `Router.startForCallback.InitData.${this._e_}`
        let initData = uni.getStorageSync(initDataKey)
        initData = initData || {
            value: null,
            config: {}
        }
        uni.removeStorageSync(initDataKey)
        this.config = initData.config || {}
        this.value = initData.value || null
        this.isInit = true
    },
    methods: {
        initCall() {
            if (!this.isInit) {
                this.$nextTick(() => {
                    this.initCall()
                })
                return
            }
            this.init()
        },
        init() {
        },
        // initPrepare() {
        //     for (let i = 0; i < arguments.length; i++) {
        //         if (i === arguments.length - 1) {
        //             if (this.isInit) {
        //                 return
        //             }
        //             this.isInit = true
        //             arguments[i]()
        //         } else {
        //             if (!arguments[i]) {
        //                 setTimeout(() => {
        //                     this.init()
        //                 }, 100)
        //                 return
        //             }
        //         }
        //     }
        // },
        callbackCancel(data) {
            uni.$emit(`Router.startForCallback.${this._e_}`, {
                type: 'cancel',
                data: data
            })
            Router.back()
        },
        callbackConfirm(data, backIgnore) {
            backIgnore = backIgnore || false
            uni.$emit(`Router.startForCallback.${this._e_}`, {
                type: 'confirm',
                data: data
            })
            if (!backIgnore) {
                Router.back()
            }
        }
    }
}
