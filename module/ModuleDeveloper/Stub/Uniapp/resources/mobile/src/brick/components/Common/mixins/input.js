export const VModelMixin = {
    model: {
        prop: 'value',
        event: 'input'
    },
    computed: {
        modelValue: {
            get() {
                return this.value
            },
            set(value) {
                this.onInput(value)
            }
        }
    },
    methods: {
        onInput(value) {
            if (typeof value === 'object') {
                if (value.type && value.target && value.detail) {
                    value = value.detail.value
                }
            }
            // console.log('onInput', value)
            // this.$emit('update:modelValue', value)
            this.$emit("input", value)
        }
    },
    props: {
        value: {
            type: [Number, String, Object, Boolean, Array],
            default: null
        },
    }
}

export const OnInputMixin = {
    methods: {
        onInput(e, a, b) {
            this.$set(a, b, e.detail.value)
        }
    }
}

/**
 * @deprecated
 */
export const InputMixin = {
    props: {
        datavName: {
            type: String,
            default: ''
        },
        value: null
    },
    methods: {
        /**
         * @param value
         * @deprecated
         */
        updateValue(value) {
            this.$emit("input", {
                target: {
                    dataset: {
                        name: this.datavName
                    }
                },
                detail: {
                    value: value,
                },
            })
        },
        updateModelValue(value) {
            this.$emit('input', value)
        }
    }
}

/**
 * @deprecated
 */
export const NumbersInputMixin = {
    props: {
        value: {
            type: Array,
            default: () => []
        },
        datavName: {
            type: String,
            default: ''
        }
    },
    watch: {
        value: {
            handler(n, o) {
                if (JSON.stringify(this.datav) !== JSON.stringify(this.value)) {
                    this.datav = this.value
                }
            },
            immediate: true
        }
    },
    // 在手机端会出现不兼容的问题，data需要放在组件中
    // data() {
    //     return {
    //         datav: null,
    //     }
    // },
    methods: {
        handleChangeProcess(v) {
            this.datav = v
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

/**
 * @deprecated
 */
export const NumberInputMixin = {
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
                }
            },
            immediate: true
        }
    },
    // 在手机端会出现不兼容的问题，data需要放在组件中
    // data() {
    //     return {
    //         datav: null,
    //     }
    // },
    methods: {
        handleChangeProcess(v) {
            this.datav = v
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

/**
 * @deprecated
 */
export const StringInputMixin = {
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
                }
            },
            immediate: true
        }
    },
    // 在手机端会出现不兼容的问题，data需要放在组件中
    // data() {
    //     return {
    //         datav: null,
    //     }
    // },
    methods: {
        handleChangeProcess(v) {
            this.datav = v
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

/**
 * @deprecated
 */
export const StringsInputMixin = {
    props: {
        value: {
            type: Array,
            default: () => []
        },
        datavName: {
            type: String,
            default: ''
        }
    },
    watch: {
        value: {
            handler(n, o) {
                if (JSON.stringify(this.datav) !== JSON.stringify(this.value)) {
                    this.datav = this.value
                }
            },
            immediate: true
        }
    },
    // 在手机端会出现不兼容的问题，data需要放在组件中
    // data() {
    //     return {
    //         datav: null,
    //     }
    // },
    methods: {
        handleChangeProcess(v) {
            this.datav = v
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
