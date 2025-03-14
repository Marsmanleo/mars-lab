import {Router} from "../../../lib/router";

export const SelectorLabelNameMixin = {
    props: {
        value: {
            type: String,
            default: null
        },
        datavName: {
            type: String,
            default: ''
        }
    },
    watch: {
        value: {
            handler(n, o) {
                if (this.datav !== this.value) {
                    this.datav = this.value
                    this.index = this.range.map(o => o.id).indexOf(this.datav)
                }
            },
            immediate: true
        }
    },
    // 在手机端会出现不兼容的问题，data需要放在组件中
    // data() {
    //     return {
    //         index: -1,
    //         datav: null,
    //     }
    // },
    methods: {
        handleChange(e) {
            this.index = e.detail.value
            if (this.index === -1) {
                this.index = 0
            }
            this.datav = this.range[this.index].id
            this.$emit('input', {
                target: {
                    dataset: {
                        name: this.datavName
                    }
                },
                detail: {
                    value: this.datav,
                },
            })
        }
    }
}

export const SelectorIdNameMixin = {
    props: {
        value: {
            type: Number,
            default: null
        },
        datavName: {
            type: String,
            default: ''
        }
    },
    watch: {
        value: {
            handler(n, o) {
                if (this.datav !== this.value) {
                    this.datav = this.value
                    this.index = this.range.map(o => o.id).indexOf(this.datav)
                }
            },
            immediate: true
        }
    },
    // 在手机端会出现不兼容的问题，data需要放在组件中
    // data() {
    //     return {
    //         index: -1,
    //         datav: null,
    //     }
    // },
    methods: {
        handleChange(e) {
            this.index = e.detail.value
            if (this.index === -1) {
                this.index = 0
            }
            this.datav = this.range[this.index].id
            this.$emit('input', {
                target: {
                    dataset: {
                        name: this.datavName
                    }
                },
                detail: {
                    value: this.datav,
                },
            })
        }
    }
}

export const SelectorIdNameDynamicMixin = {
    props: {
        value: {
            type: Number,
            default: 0
        },
        datavName: {
            type: String,
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false,
        }
    },
    watch: {
        value: {
            handler(n, o) {
                if (this.datav !== this.value || !this.isInit) {
                    this.isInit = true
                    this.datav = this.value
                    this.prepareValue()
                }
            },
            immediate: true
        }
    },
    // 在手机端会出现不兼容的问题，data需要放在组件中
    // data() {
    //     return {
    //         isInit: false,
    //         datav: 0,
    //         loading: false,
    //         record: null,
    //     }
    // },
    methods: {
        prepareValue() {
            if (!this.datav) {
                this.record = null
                return
            }
            this.loading = true
            this.prepareValueProcess(record => {
                this.record = record
                this.loading = false
            })
        },
        prepareValueProcess(cb) {
            cb && cb(null)
        },
        doSelectProcess(url, config, param) {
            Router.startForCallback(url, 0, config || {}, data => {
                this.$emit('input', {
                    target: {
                        dataset: {
                            name: this.datavName
                        }
                    },
                    detail: {
                        value: data,
                    },
                })
            }, data => {
            }, param || {})
        }
    }
}
